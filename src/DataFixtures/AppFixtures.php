<?php

namespace App\DataFixtures;

use App\Entity\{ ICUser, Post, Action, Friend };
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userAaliyah = new ICUser();
        $userAaliyah->setFirstName('Test')
                ->setLastName('Aaliyah')
                ->setEmail('test@aaliyah.test')
                ->setProfilePic('female_avatar.jpg')
                ->setBirthDate(new \DateTime())
                ->setPassword(password_hash('test', PASSWORD_BCRYPT))
                ->setPermalink('aaliyah')
                ->setGender('female');
        $manager->persist($userAaliyah);

        $userJohn = new ICUser();
        $userJohn->setFirstName('Test')
                ->setLastName('John')
                ->setEmail('test@john.test')
                ->setProfilePic('male_avatar.jpg')
                ->setBirthDate(new \DateTime())
                ->setPassword(password_hash('test', PASSWORD_BCRYPT))
                ->setPermalink('john')
                ->setGender('male');
        $manager->persist($userJohn);

        $userCharles = new ICUser();
        $userCharles->setFirstName('Test')
            ->setLastName('Charles')
            ->setEmail('test@charles.test')
            ->setProfilePic('male_avatar2.jpg')
            ->setBirthDate(new \DateTime())
            ->setPassword(password_hash('test', PASSWORD_BCRYPT))
            ->setPermalink('charles')
            ->setGender('male');
        $manager->persist($userCharles);

        $userAda = new ICUser();
        $userAda->setFirstName('Test')
            ->setLastName('Ada')
            ->setEmail('test@ada.test')
            ->setProfilePic('female_avatar2.jpg')
            ->setBirthDate(new \DateTime())
            ->setPassword(password_hash('test', PASSWORD_BCRYPT))
            ->setPermalink('ada')
            ->setGender('female');
        $manager->persist($userAda);
        $manager->flush();

        // Getting the id of a user for creating a post
        $user = $manager->getRepository(ICUser::class)
                ->findOneBy([ 'email' => 'test@aaliyah.test' ]);

        $firstPost = new Post();
        $firstPost->setUserId($user->getUserId())
                ->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.')
                ->setDateOfUpload(new \DateTime());
        $manager->persist($firstPost);

        $manager->flush();
    }
}