<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class InfoController extends Controller
{
    /**
     * @Route("/info/news", name="news", methods={ "GET" })
     */
    public function news()
    {
        return new Response('news');
    }

    /**
     * @Route("/info/events", name="events", methods={ "GET" })
     */
    public function events()
    {
        return new Response('events');
    }

    /**
     * @Route("/info/findfriends", name="find_friends", methods={ "GET" })
     */
    public function findfriends()
    {
        return new Response('findfriends');
    }

    /**
     * @Route("/info/about", name="about", methods={ "GET" })
     */
    public function about()
    {
        return new Response('about');
    }

    /**
     * @Route("/info/privacy", name="privacy", methods={ "GET" })
     */
    public function privacy()
    {
        return new Response('privacy');
    }

    /**
     * @Route("/info/terms", name="terms", methods={ "GET" })
     */
    public function terms()
    {
        return new Response('terms');
    }

    /**
     * @Route("/info/cookies", name="cookies", methods={ "GET" })
     */
    public function cookies()
    {
        return new Response('cookies');
    }

    /**
     * @Route("/info/help", name="help", methods={ "GET" })
     */
    public function help()
    {
        return new Response('help');
    }
}