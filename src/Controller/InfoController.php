<?php

namespace App\Controller;

use App\Entity\ICUser;
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
        return new JsonResponse([
            'result' => $this->getDoctrine()
                            ->getManager()
                            ->getRepository(ICUser::class)
                            ->findByName($request->query->get('query'))
        ]);
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