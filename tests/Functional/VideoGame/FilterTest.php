<?php

declare(strict_types=1);

namespace App\Tests\Functional\VideoGame;

use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;

final class FilterTest extends FunctionalTestCase
{
    public function testShouldListTenVideoGames(): void
    {
        $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(10, 'article.game-card');
        $this->client->clickLink('2');
        self::assertResponseIsSuccessful();
    }

    public function testShouldFilterVideoGamesBySearch(): void
    {
        $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(10, 'article.game-card');
        $this->client->submitForm('Filtrer', ['filter[search]' => 'Jeu vidéo 49'], 'GET');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(1, 'article.game-card');
    }

     /**
     * @dataProvider tagsDataProvider
     * @param int[] $tags
     */
    public function testShouldFilterVideoGamesByTags(array $tags, int $expectedCount): void
    {
        $crawler = $this->get('/');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount(10, 'article.game-card');

        $form = $crawler->selectButton('Filtrer')->form();

        /** @var ChoiceFormField */
        foreach ($form['filter[tags]'] as $tagElement) {
            if (!in_array($tagElement->availableOptionValues()[0], $tags)) {
                continue;
            }

            $tagElement->tick();
        }
        $this->client->submitForm('Filtrer', $form->getValues(), 'GET');
        self::assertResponseIsSuccessful();
        self::assertSelectorCount($expectedCount, 'article.game-card');
    }

    /**
     * @return array<string, array{tags: int[], expectedCount: int}>
     */
    public function tagsDataProvider(): array
    {
        return [
            'No tags specified' => [
                'tags' => [],
                'expectedCount' => 10,
            ],
            'Single tag, matching games' => [
                'tags' => [132],
                'expectedCount' => 10,
            ],
            'Multiple tags, matching games' => [
                'tags' => [132, 133],
                'expectedCount' => 9,
            ],
            'Multiple tags, no matching games' => [
                'tags' => [132, 133, 137],
                'expectedCount' => 0,
            ],
        ];
    }
}
