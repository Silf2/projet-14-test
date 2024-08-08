<?php

namespace App\Tests\Unit;

use App\Model\Entity\Review;
use App\Model\Entity\VideoGame;
use App\Rating\RatingHandler;
use PHPUnit\Framework\TestCase;

class AverageCalculatorTest extends TestCase
{
    /**
     * @dataProvider provideVideoGame
     */
    public function testAverageCalculator(VideoGame $videoGame, ?int $expectedAverageRating): void
    {
        $ratingHandler = new RatingHandler();
        $ratingHandler->calculateAverage($videoGame);

        self::assertSame($expectedAverageRating, $videoGame->getAverageRating());
    }

    /**
     * @return iterable<array{VideoGame, ?int}>
     */
    public static function provideVideoGame(): iterable
    {
        yield 'No review' => [new VideoGame(), null];

        yield 'One review' => [self::createVideoGame(5), 5];

        yield 'Many reviews' => [
            self::createVideoGame(1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5),
            4
        ];
    }

    private static function createVideoGame(int ...$ratings): VideoGame
    {
        $videoGame = new VideoGame();

        foreach($ratings as $rating) {
            $videoGame->getReviews()->add((new Review())->setRating($rating));
        }

        return $videoGame;
    }
}