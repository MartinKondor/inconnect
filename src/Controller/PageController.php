<?php

namespace App\Controller;

use App\Entity\{ User, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class PageController extends Controller
{
    // TODO Can be linked by a permalink if the page need's it, it can define one
    /**
     * @Route("/pages/{pageId}", name="view_page", methods={ "GET" })
     */
    public function viewPage($pageId)
    {
        return $this->render('pages/view.html.twig', [

        ]);
    }

    /**
     * @Route("/pages/create", name="create_page", methods={ "GET", "POST" })
     */
    public function create()
    {
        return $this->render('pages/create.html.twig', [

        ]);
    }
}