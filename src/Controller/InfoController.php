<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class InfoController extends Controller
{
    /**
     * @Route("/search", name="search", methods={ "POST" })
     */
    public function search()
    {
        $query = $this->getDoctrine()->getManager()->getConnection()->prepare("SELECT profile_pic, permalink,
                                                                      first_name, last_name FROM user
                                                                      WHERE first_name LIKE :queryFirst OR 
                                                                      last_name LIKE :queryLast LIMIT 5;");
        if (preg_match('/.*\s{1}.*/', $_POST['query'])) {
            list($queryFirst, $queryLast) = explode(' ', $_POST['query']);
            $query->execute([ ':queryFirst' => "%$queryFirst%", ':queryLast' => "%$queryLast%" ]);
        } else {
            $query->execute([ ':queryFirst' => "%{$_POST['query']}%", ':queryLast' => "%{$_POST['query']}%" ]);
        }
        return new JsonResponse([ 'result' => $query->fetchAll() ]);
    }

    /**
     * @Route("/news", name="news", methods={ "GET" })
     */
    public function news()
    {
        return new Response('news');
    }

    /**
     * @Route("/events", name="events", methods={ "GET" })
     */
    public function events()
    {
        return new Response('events');
    }

    /**
     * @Route("/findfriends", name="find_friends", methods={ "GET" })
     */
    public function findfriends()
    {
        return new Response('findfriends');
    }

    /**
     * @Route("/about", name="about", methods={ "GET" })
     */
    public function about()
    {
        return new Response('about');
    }

    /**
     * @Route("/privacy", name="privacy", methods={ "GET" })
     */
    public function privacy()
    {
        return new Response('privacy');
    }

    /**
     * @Route("/terms", name="terms", methods={ "GET" })
     */
    public function terms()
    {
        return new Response('terms');
    }

    /**
     * @Route("/cookies", name="cookies", methods={ "GET" })
     */
    public function cookies()
    {
        return new Response('cookies');
    }

    /**
     * @Route("/help", name="help", methods={ "GET" })
     */
    public function help()
    {
        return new Response('help');
    }
}