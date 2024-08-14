<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = [];
        for ($i = 0; $i < 20; $i++) {
            $tag = (new Tag)->setName(sprintf('Tag %d', $i)); 
            $tags[] = $tag;
            $manager->persist($tag);
        }

        $manager->flush();
    }
}