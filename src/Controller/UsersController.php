<?php

namespace App\Controller;

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

      $viewUser = [
         'link' => 'userlink',
         'name' => 'User' . ' ' . 'Name',
         'first_name' => 'first_name',
         'last_name' => 'last_name',
         'about' => 'about',
         'email' => 'email',
         'gender' => 'male',
         'birthDate' => '0000 / January / 01',
         'profilePic' => 'default.png',
         'friends' => [],
         'posts' => []
      ];

      return $this->render('users/view.html.twig', [
         'viewUser' => $viewUser
      ]);
   }
}
