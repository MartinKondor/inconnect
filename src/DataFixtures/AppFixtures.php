<?php

namespace App\DataFixtures;

use App\Entity\{ User, Post, Action, Friend };
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userClara = new User();
        $userClara->setFirstName('Test');
        $userClara->setLastName('Clara');
        $userClara->setEmail('test@clara.test');
        $userClara->setProfilePic('female_avatar.jpg');
        $userClara->setBirthDate(new \DateTime());
        $userClara->setPassword(password_hash('test', PASSWORD_BCRYPT));
        $userClara->setPermalink('testclara');
        $userClara->setGender('female');

        $userJohn = new User();
        $userJohn->setFirstName('Test');
        $userJohn->setLastName('John');
        $userJohn->setEmail('test@john.test');
        $userJohn->setProfilePic('male_avatar.jpg');
        $userJohn->setBirthDate(new \DateTime());
        $userJohn->setPassword(password_hash('test', PASSWORD_BCRYPT));
        $userJohn->setPermalink('testjohn');
        $userJohn->setGender('male');

        $manager->persist($userClara);
        $manager->persist($userJohn);
        $manager->flush();
    }
}