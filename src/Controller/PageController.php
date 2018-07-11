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
     * @Route("/pages/{pagePermalink}", name="view_page", methods={ "GET" })
     */
    public function viewPage($pagePermalink)
    {
        $user = $this->getUser();
        $connection = $this->getDoctrine()->getManager()->getConnection();

        // Get all info about the page and all posts posted by the page
        $pageQuery = $connection->prepare("SELECT * FROM page
                                          LEFT JOIN post 
                                          ON post.holder_type = 'page'
                                          WHERE page.page_permalink = :page_permalink");
        $pageQuery->execute([
            ':page_permalink' => $pagePermalink
        ]);
        $viewPage = $pageQuery->fetch();

        if (empty($viewPage))
            throw new NotFoundHttpException('Page not found');

        $viewPage['upvotedByUser']= true;

        return $this->render('pages/view.html.twig', [
            'viewPage' => $viewPage
        ]);
    }

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
            $page->setCreatorUserId($creatorId);
            $page->setDateOfCreation(new \DateTime());
            $page->setPassword(password_hash($page->getPassword(), PASSWORD_BCRYPT));
            $page->setPagePermalink($page->getPageName());

            $em->persist($page);
            $em->flush();
            return $this->redirectToRoute('view_page', [ 'pagePermalink' => $page->getPagePermalink() ]);
        }

        return $this->render('pages/create.html.twig', [
            'page_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/pages/{pageId}/post", name="post_with_page", methods={ "POST" })
     */
    public function postWithPage($pageId)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository(Page::class)->findOneBy([ 'page_id' => $pageId ]);

        // See if the user is the creator of the requested page, if not then redirect to index
        if ($user->getUserId() !== $page->getCreatorUserId())
            return $this->redirectToRoute('index');

        // Save the post
        $post = new Post();
        $post->setUserId($user->getUserId());
        $post->setContent($_POST['page_post_content']);
        $post->setDateOfUpload(new \DateTime());
        $post->setHolderType('page');

        $em->persist($post);
        $em->flush();

        return new Response('ok');
    }
}