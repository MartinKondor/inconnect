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
    public function search(Request $request)
    {
        $query = $this->getDoctrine()
                    ->getManager()
                    ->getConnection()
                    ->prepare("SELECT profile_pic, permalink, first_name, last_name
                               FROM icuser
                               WHERE first_name LIKE :queryFirst OR 
                               last_name LIKE :queryLast LIMIT 5;");

        // If the search contains first and last name split it for the query
        if (preg_match('/.*\s{1}.*/', $request->query->get('query'))) {

            list($queryFirst, $queryLast) = explode(' ', $request->query->get('query'));

            $query->execute([
                ':queryFirst' => "%$queryFirst%",
                ':queryLast' => "%$queryLast%"
            ]);
        } else {
            $query->execute([
                ':queryFirst' => "%{$request->query->get('query')}%",
                ':queryLast' => "%{$request->query->get('query')}%"
            ]);
        }

        return new JsonResponse([ 'result' => $query->fetchAll() ]);
    }

    /**
     * @Route("/events", name="events", methods={ "GET" })
     */
    public function events()
    {
        return $this->render('info/events.html.twig', []);
    }

    /**
     * @Route("/findfriends", name="find_friends", methods={ "GET" })
     */
    public function findfriends()
    {
        return $this->render('info/findfriends.html.twig', []);
    }

    /**
     * @Route("/about", name="about", methods={ "GET" })
     */
    public function about()
    {
        return $this->redirect('https://github.com/in-connect/inconnect#readme');
        // return $this->render('info/about.html.twig', []);
    }

    /**
     * @Route("/privacy", name="privacy", methods={ "GET" })
     */
    public function privacy()
    {
        return $this->render('info/privacy.html.twig', []);
    }

    /**
     * @Route("/terms", name="terms", methods={ "GET" })
     */
    public function terms()
    {
        return $this->render('info/terms.html.twig', []);
    }

    /**
     * @Route("/cookies", name="cookies", methods={ "GET" })
     */
    public function cookies()
    {
        return $this->render('info/cookies.html.twig', []);
    }

    /**
     * @Route("/help", name="help", methods={ "GET" })
     */
    public function help()
    {
        return $this->render('info/help.html.twig', []);
    }
}