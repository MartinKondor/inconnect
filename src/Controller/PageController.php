<?php

namespace App\Controller;

use App\Form\PageType;

use App\Entity\{ User, Post, Action, Page };

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };
use Symfony\Component\Validator\Constraints\DateTime;
use Zend\Code\Generator\ParameterGenerator;

class PageController extends Controller
{
    /**
     * @Route("/create/pages", name="create_page", methods={ "GET", "POST" })
     */
    public function create(Request $request)
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page, [
            'action' => $this->generateUrl('create_page')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $creatorId = $this->getUser()->getUserId();
            $page->setCreatorUserId($creatorId)
                ->setDateOfCreation(new \DateTime())
                ->setPassword(password_hash($page->getPassword(), PASSWORD_BCRYPT))
                ->setPagePermalink($page->getPageName());

            $em->persist($page);
            $em->flush();
            return $this->redirectToRoute('view_page', [
                'pageId' => $page->getPageId(),
                'pagePermalink' => $page->getPagePermalink()
            ]);
        }

        return $this->render('pages/create.html.twig', [
            'page_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pages/{pageId}/{pagePermalink}", name="view_page", methods={ "GET" })
     */
    public function viewPage($pageId, $pagePermalink)
    {
        $user = $this->getUser();
        $connection = $this->getDoctrine()->getManager()->getConnection();

        // Get all info about the page and all posts posted by the page
        $pageQuery = $connection->prepare("SELECT * FROM page
                                          LEFT JOIN post 
                                          ON post.holder_type = 'page'
                                          WHERE page.page_id = :page_id
                                          AND page.page_permalink = :page_permalink");
        $pageQuery->execute([
            ':page_id' => $pageId,
            ':page_permalink' => $pagePermalink
        ]);
        $viewPage = $pageQuery->fetch();

        if (empty($viewPage))
            throw new NotFoundHttpException('Page not found');

        // Search for the page's posts by the creator user's posts
        $pagePostQuery = $connection->prepare("SELECT * FROM post
                                            LEFT JOIN page
                                            ON page.creator_user_id = post.user_id
                                            WHERE post.user_id = :creator_user_id
                                            AND post.holder_type = 'page'
                                            ORDER BY post.date_of_upload DESC");
        $pagePostQuery->execute([ ':creator_user_id' => $viewPage['creator_user_id'] ]);
        $posts = $pagePostQuery->fetchAll();
        
        // Setting up posts for template
        foreach ($posts as $i => $post) {
            $postQuery = $connection->prepare("SELECT user.user_id, user.first_name, user.last_name, user.permalink, 
                                                user.profile_pic, `action`.`action_type`, `action`.`action_date`, `action`.`content`
                                                FROM `action` RIGHT JOIN user
                                                ON `action`.`user_id` = user.user_id
                                                WHERE `action`.`entity_id` = :entity_id
                                                AND (`action`.`action_type` = 'comment' OR `action`.`action_type` = 'upvote')
                                                AND `action`.`entity_type` = 'post'");
            $postQuery->execute([
                ':entity_id' => $post['post_id']
            ]);
            $actions = $postQuery->fetchAll();

            $upvotes = 0;
            $comments = null;
            $posts[$i]['isUpvotedByUser'] = false;

            foreach ($actions as $action) {
                if ($action['action_type'] === 'comment') $comments[] = $action;
                if ($action['action_type'] === 'upvote') {
                    $upvotes++;
                    if ($user->getUserId() == $action['user_id'])
                        $posts[$i]['isUpvotedByUser'] = true;
                }
            }

            $posts[$i]['upvotes'] = $upvotes;
            $posts[$i]['comments'] = $comments;
        }

        $viewPage['posts'] = $posts;
        $viewPage['upvotedByUser'] = true;

        return $this->render('pages/view.html.twig', [
            'viewPage' => $viewPage
        ]);
    }

    /**
     * @Route("/pages/{pageId}/{pagePermalink}/post", name="post_with_page", methods={ "POST" })
     */
    public function postWithPage($pageId, $pagePermalink)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository(Page::class)
            ->findOneBy([
                'page_id' => $pageId,
                'page_permalink' => $pagePermalink
            ]);

        // See if the user is the creator of the requested page, if not then redirect to index
        if ($user->getUserId() !== $page->getCreatorUserId())
            return $this->redirectToRoute('index');

        // Save the post
        $post = new Post();
        $post->setUserId($user->getUserId())
            ->setContent($_POST['page_post_content'])
            ->setDateOfUpload(new \DateTime())
            ->setHolderType('page');

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('view_page', [
            'pageId' => $page->getPageId(),
            'pagePermalink' => $page->getPagePermalink()
        ]);
    }

    /**
     * @Route("/pages/{pageId}/{pagePermalink}/settings", name="page_settings", methods={ "GET", "POST" })
     */
    public function pageSettings($pageId, $pagePermalink)
    {
        return $this->render('pages/settings.html.twig', []);
    }
}