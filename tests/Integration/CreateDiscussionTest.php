<?php

namespace App\Tests\Integration;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateDiscussionTest extends WebTestCase
{
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient(array_merge($options, ['environment' => 'test']), $server);
    }

    static public function data(): array
    {
        $members = [
            MemberRepository::parse([
                'email' => 'arnaud+create-discussion@email.com',
                'userIdentifier' => 'arnaud+create-discussion',
                'username' => 'Arnaud'
            ])];
        return [
            'members' => $members
        ];
    }
    public function testSuccessful()
    {
        $client = static::createClient();
        $client->loginUser(new User(
            'username',
            'user@mail.com',
            'fullname',
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $crawler = $client->request(Request::METHOD_GET, '/discussions/create');

        $this->assertResponseIsSuccessful();
        $crawler->filter('form')->each(function (Crawler $heads) {
            foreach ($heads as $head) {
                $element = $head->parentNode->ownerDocument->createElement('input');
                $element->setAttribute('name', 'discussion[emails][0]');
                $head->appendChild($element);

                $element = $head->parentNode->ownerDocument->createElement('input');
                $element->setAttribute('name', 'discussion[emails][1]');
                $head->appendChild($element);
            }
        });

        $form = $crawler->filter('form')->form();
        $client->submit($form, [
            'discussion[name]' => 'name',
            'discussion[emails][0]' => 'david+create-discussion@email.com',
            'discussion[emails][1]' => 'arnaud+create-discussion@email.com'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @dataProvider provideFormData
     * @param string $name
     * @param array $emails
     * @param string $errorMessage
     * @return void
     * @throws \DOMException
     */
    public function testFailed(string $name, array $emails,string $errorMessage)
    {

        $client = static::createClient();
        $client->loginUser(new User(
            'username',
            'user@mail.com',
            'fullname',
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $crawler = $client->request(Request::METHOD_GET, '/discussions/create');

        $this->assertResponseIsSuccessful();
        $crawler->filter('form')->each(function (Crawler $heads) use ($emails) {
            foreach ($heads as $head) {
                foreach ($emails as $key => $email) {
                    $element = $head->parentNode->ownerDocument->createElement('input');
                    $element->setAttribute('name', "discussion[emails][$key]");
                    $head->appendChild($element);
                }
            }
        });

        $form = $crawler->filter('form')->form();
        $form->setValues([
            'discussion[name]' => $name
        ]);
        foreach ($emails as $key => $email) {
            $form->setValues([
                "discussion[emails][$key]" => $email
            ]);
        }
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('html', $errorMessage);
    }

    public function provideFormData(): \Generator
    {
        yield ['', ['member1@mail.com'], 'This value should not be blank.'];
        yield ['name', ['member1mail.com'], 'This value is not a valid email address.'];
        yield ['name', [''], 'This value should not be blank.'];
        yield ['name', [], 'This collection should contain 1 element or more.'];
    }
}
