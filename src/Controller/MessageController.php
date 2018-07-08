<?php

namespace App\Controller;

use App\Entity\{ User, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class MessageController extends Controller
{
    /**
     * @Route("/messages", name="messages", methods={ "GET", "POST" })
     */
    public function messages()
    {
        return $this->render('users/messages.html.twig', [

        ]);
    }
}