<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Action;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller 
{
   /**
    * @Route("/signup", name="signup", methods={ "POST" })
    */
   public function signup()
   {
      $signupErrors = [];

      if (empty($_POST['firstname']))
         $signupErrors[] = 'Please provide your first name.';
      if (empty($_POST['lastname']))
         $signupErrors[] = 'Please provide your last name.';
      if (empty($_POST['email']) or !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
         $signupErrors[] = 'The email should be a real email address.';
      if (empty($_POST['password']))
         $signupErrors[] = 'The password field cannot be empty.';
      if (empty($_POST['birth_year']) or empty($_POST['birth_month']) or empty($_POST['birth_day']))
         $signupErrors[] = 'Please provide your birthdate.';
      if (empty($_POST['gender']))
         $signupErrors[] = 'You must choose a gender.';

      $isUserAlreadyExists = $this->getDoctrine()
                              ->getRepository(User::class)
                              ->findOneBy([ 'email' => $_POST['email'] ]);
      if (isset($isUserAlreadyExists))
         $signupErrors[] = 'A User with this email address is already exists.';

      if ($signupErrors !== []) {
            
         return $this->render('main/index.html.twig', [
            'signUpErrors' => $signupErrors
         ]);
      } else {
         
         // Save user to the database & send an email & log in
         $em = $this->getDoctrine()->getManager();
         
         $user = new User();
         $user->setName(ucfirst(strtolower($_POST['firstname'])) . ' ' . ucfirst(strtolower($_POST['lastname'])));
         $user->setEmail($_POST['email']);
         $user->setPassword(\password_hash($_POST['password'], PASSWORD_BCRYPT));
         $user->setBirthDate(new \DateTime("{$_POST['birth_year']}-{$_POST['birth_month']}-{$_POST['birth_day']}"));
         $user->setGender($_POST['gender']);
         $user->setPermalink(strtolower($_POST['email']) . $_POST['birth_day']);

         $em->persist($user);
         $em->flush();

         return $this->render('main/index.html.twig', []);
      }
   }

   /**
    * @Route("/login", name="login", methods={ "POST" })
    */
   public function login()
   {
      // TODO
   }
}