<?php

namespace Tests\Functional\VideoGame;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\HttpFoundation\Response;

final class ReviewTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function login(string $email = 'user+0@email.com'): void
    {
        $user = $this->client->getContainer()->get(EntityManagerInterface::class)->getRepository(User::class)->findOneBy(["email" => $email]);
        $this->client->loginUser($user);
    }

    public function testShouldPostReview(): void
    {
        $this->login();
        $crawler = $this->client->request('GET', 'jeu-video-44');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Poster')->form([
            'review[rating]' => 5,
            'review[comment]' => 'Super commentaire de test',
        ]);

        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $this->client->followRedirect();
        $this->assertSelectorTextContains('div.list-group-item:last-child h3', 'user+0');
        $this->assertSelectorTextContains('div.list-group-item:last-child p', 'Super commentaire de test');
        $this->assertSelectorTextContains('div.list-group-item:last-child span.value', '5');

        //Check of the db
        $review = $this->client->getContainer()->get('doctrine')->getRepository(Review::class)->findOneBy([
            'comment' => 'Super commentaire de test',
        ]);
        $this->assertNotNull($review);
        $this->assertSame(5, $review->getRating());

        //Check that user can't post two reviews on the same game
        $crawler = $this->client->request('GET', 'jeu-video-44');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorNotExists('form[name="review"]', 'Le formulaire d\'ajout de review ne doit pas être visible pour les utilisateurs ayant déjà posté une review');
    }

    public function testShouldNotPostInvalidReview(): void
    {
        $this->login();
        $crawler = $this->client->request('GET', 'jeu-video-43');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Poster')->form();
        /** @var ChoiceFormField $note */
        $note = $form['review[rating]'];
        $note->disableValidation()->select('6');

        $this->client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testShouldOnlyAllowAuthenticatedUsersToPostReview(): void
    {
        $this->client->request('GET', '/jeu-video-41');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorNotExists('form[name="review"]', 'Le formulaire d\'ajout de review ne doit pas être visible pour les utilisateurs non authentifiés.');
    }
}