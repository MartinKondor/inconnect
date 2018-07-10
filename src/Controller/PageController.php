<?php

namespace App\Controller;

use App\Form\PageType;

use App\Entity\{ User, Post, Action, Page };

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };
use Zend\Code\Generator\ParameterGenerator;

class PageController extends Controller
{
    // TODO Can be linked by a permalink if the page need's it, it can define one
    /**
     * @Route("/pages/{pageId}", name="view_page", methods={ "GET" })
     */
    public function viewPage($pageId)
    {
        $user = $this->getUser();

        $viewPage = $this->getDoctrine()
                ->getRepository(Page::class)
                ->findOneBy([ 'page_id' => $pageId ]);

        if (empty($viewPage))
            throw new NotFoundHttpException('Page not found');

        $viewPage->upvotedByUser = true;

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

            $em->persist($page);
            $em->flush();
            return $this->redirectToRoute('view_page', [ 'pageId' => $page->getPageId() ]);
        }

        return $this->render('pages/create.html.twig', [
            'page_form' => $form->createView()
        ]);
    }
}