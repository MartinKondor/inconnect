<?php

namespace App\Controller;

use App\Entity\{ Friend, ICUser };
use PHPUnit\Util\Json;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{
    RedirectResponse, Response, JsonResponse, Request
};

class InfoController extends Controller
{
    /**
     * @Route("/search", name="search", methods={ "POST" })
     */
    public function search(Request $request): JsonResponse
    {
        return new JsonResponse([
            'result' => $this->getDoctrine()
                            ->getManager()
                            ->getRepository(ICUser::class)
                            ->findByName($request->request->get('query'))
        ]);
    }

    /**
     * @Route("/events", name="events", methods={ "GET" })
     */
    public function events(): Response
    {
        return $this->render('info/events.html.twig', []);
    }

    /**
     * @Route("/friends", name="user_friends", methods={ "GET" })
     */
    public function userFriends(): Response
    {
        return $this->render('info/friends.html.twig', [
            'friends' => $this->getDoctrine()
                            ->getRepository(Friend::class)
                            ->findFriends($this->getUser()->getUserId())
        ]);
    }

    /**
     * @Route("/findfriends", name="find_friends", methods={ "GET" })
     */
    public function findfriends(): Response
    {
        return $this->render('info/findfriends.html.twig', []);
    }

    /**
     * @Route("/about", name="about", methods={ "GET" })
     */
    public function about(): RedirectResponse
    {
        return $this->redirect('https://github.com/in-connect/inconnect#readme');
        // return $this->render('info/about.html.twig', []);
    }

    /**
     * @Route("/privacy", name="privacy", methods={ "GET" })
     */
    public function privacy(): Response
    {
        return $this->render('info/privacy.html.twig', []);
    }

    /**
     * @Route("/terms", name="terms", methods={ "GET" })
     */
    public function terms(): Response
    {
        return $this->render('info/terms.html.twig', []);
    }

    /**
     * @Route("/cookies", name="cookies", methods={ "GET" })
     */
    public function cookies(): Response
    {
        return $this->render('info/cookies.html.twig', []);
    }

    /**
     * @Route("/help", name="help", methods={ "GET" })
     */
    public function help(): Response
    {
        return $this->render('info/help.html.twig', []);
    }
}