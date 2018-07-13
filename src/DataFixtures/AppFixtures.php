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
        $userClara->setFirstName('Test')
                ->setLastName('Aaliyah')
                ->setEmail('test@aaliyah.test')
                ->setProfilePic('female_avatar.jpg')
                ->setBirthDate(new \DateTime())
                ->setPassword(password_hash('test', PASSWORD_BCRYPT))
                ->setPermalink('~aaliyah')
                ->setGender('female');

        $userJohn = new User();
        $userJohn->setFirstName('Test')
                ->setLastName('John')
                ->setEmail('test@john.test')
                ->setProfilePic('male_avatar.jpg')
                ->setBirthDate(new \DateTime())
                ->setPassword(password_hash('test', PASSWORD_BCRYPT))
                ->setPermalink('~john')
                ->setGender('male');

        $manager->persist($userClara);
        $manager->persist($userJohn);
        $manager->flush();
    }
}