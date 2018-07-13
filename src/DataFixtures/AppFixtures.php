<?php

namespace App\DataFixtures;

use App\Entity\{ User, Post, Action, Friend };
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Creating two users
        $userAaliyah = new User();
        $userAaliyah->setFirstName('Test')
                ->setLastName('Aaliyah')
                ->setEmail('test@aaliyah.test')
                ->setProfilePic('female_avatar.jpg')
                ->setBirthDate(new \DateTime())
                ->setPassword(password_hash('test', PASSWORD_BCRYPT))
                ->setPermalink('~aaliyah')
                ->setGender('female');
        $manager->persist($userAaliyah);

        $userJohn = new User();
        $userJohn->setFirstName('Test')
                ->setLastName('John')
                ->setEmail('test@john.test')
                ->setProfilePic('male_avatar.jpg')
                ->setBirthDate(new \DateTime())
                ->setPassword(password_hash('test', PASSWORD_BCRYPT))
                ->setPermalink('~john')
                ->setGender('male');
        $manager->persist($userJohn);
        $manager->flush();

        // Getting the id of a user for creating a post
        $user = $manager->getRepository(User::class)
                ->findOneBy([ 'email' => 'test@aaliyah.test' ]);

        $firstPost = new Post();
        $firstPost->setUserId($user->getUserId())
                ->setContent('Content of the first post.')
                ->setDateOfUpload(new \DateTime());
        $manager->persist($firstPost);

        $manager->flush();
    }
}