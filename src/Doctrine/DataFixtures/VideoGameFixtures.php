<?php

namespace App\Doctrine\DataFixtures;

use App\Model\Entity\Review;
use App\Model\Entity\Tag;
use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use App\Rating\CalculateAverageRating;
use App\Rating\CountRatingsPerValue;
use DateInterval;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

final class VideoGameFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly Generator $faker,
        private readonly CalculateAverageRating $calculateAverageRating,
        private readonly CountRatingsPerValue $countRatingsPerValue
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $tags = $manager->getRepository(Tag::class)->findAll();

        $users = array_chunk(
            $manager->getRepository(User::class)->findAll(),
            5
        );

        $fakerDescAndTest = $this->faker->paragraph(5, true);

        $videoGames = [];
        for ($i = 0; $i < 50; $i++) {
            $videoGame = (new VideoGame)
                ->setTitle(sprintf('Jeu vidéo %d', $i))
                ->setDescription($fakerDescAndTest)
                ->setReleaseDate((new DateTimeImmutable())->sub(new DateInterval(sprintf('P%dD', $i))))
                ->setTest($fakerDescAndTest)
                ->setRating(($i % 5) + 1)
                ->setImageName(sprintf('video_game_%d.png', $i))
                ->setImageSize(2_098_872);

            // Ajout des tags
            for ($tagCount = 0; $tagCount < 5; $tagCount++) {
                $videoGame->getTags()->add($tags[($i + $tagCount) % count($tags)]);
            }

            $videoGames[] = $videoGame;
            $manager->persist($videoGame);
        }

        $manager->flush();

        foreach ($videoGames as $index => $videoGame) {
            $filteredUsers = $users[$index % 5];

            foreach ($filteredUsers as $i => $user) {
                $comment = $this->faker->paragraph(1, true);

                $review = (new Review())
                    ->setUser($user)
                    ->setVideoGame($videoGame)
                    ->setRating($this->faker->numberBetween(1, 5))
                    ->setComment($comment)
                ;

                $videoGame->getReviews()->add($review);
                $manager->persist($review);

                $this->calculateAverageRating->calculateAverage($videoGame);
                $this->countRatingsPerValue->countRatingsPerValue($videoGame);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TagFixtures::class, UserFixtures::class];
    }
}
