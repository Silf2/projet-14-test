<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [];
        for ($i = 0; $i < 25; $i++) {
            $user = (new User)
                ->setEmail(sprintf('user+%d@email.com', $i))
                ->setPlainPassword('password')
                ->setUsername(sprintf('user+%d', $i));
            
            $users[] = $user;
            $manager->persist($user);
        }

        $manager->flush();
    }
}
