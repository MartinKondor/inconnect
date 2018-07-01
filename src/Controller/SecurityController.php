<?php

namespace App\Controller;

use App\Entity\{ User, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller 
{
   /**
    * @Route("/login", name="login", methods={ "POST" })
    */
   public function login()
   {
      $loginErrors = [];

      if (empty($_POST['email']) or !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
         $loginErrors[] = 'The email field cannot be empty.';
      if (empty($_POST['password']))
         $loginErrors[] = 'The password field cannot be empty.';

      $requiredUser = $this->getDoctrine()
                        ->getRepository(User::class)
                        ->findOneBy([ 'email' => $_POST['email'] ]);

      if (empty($requiredUser) or !password_verify($_POST['password'], $requiredUser->getPassword()))
         $loginErrors[] = 'Wrong password or email address, please try again.';
   
      if ($loginErrors !== []) {

         return $this->render('main/index.html.twig', [
            'loginErrors' => $loginErrors
         ]);
      } else {
         $this->get('session')->set('loginUserId', $user['user_id']);
         return $this->redirectToRoute('home');
      } 
   }
}