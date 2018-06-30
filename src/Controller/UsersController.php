<?php

namespace App\Controller;

use App\Entity\User;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller 
{
   /**
    * @Route("/u/{permalink}", name="view_user", methods={ "GET" })
    */
   public function viewUserAction(string $permalink)
   {
      $viewUser = $this->getDoctrine()
                  ->getRepository(User::class)
                  ->findOneBy([ 'permalink' => $permalink ]);

      if (empty($viewUser))
         throw $this->createNotFoundException('The user does not exists.');

      return $this->render('users/view.html.twig', [
         'viewUser' => $viewUser
      ]);
   }
}
