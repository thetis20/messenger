<?php

namespace App\Tests\Integration;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Adapter\Repository\MessageRepository;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Messenger\Domain\Entity\Discussion;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Entity\Message;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class ProfileTest extends WebTestCase
{
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient(array_merge($options, ['environment' => 'test']), $server);
    }

    /**
     * @return void
     */
    public function testSuccessful()
    {
        $client = static::createClient();
        $client->loginUser(new User(
            'f9804988-e302-4788-8500-dd0822243cce',
            'arnaud@email.com',
            'Arnaud Picard',
            ['ROLE_USER']
        ), 'main', [
            TokensBag::class => new TokensBag('accessToken', 'refreshToken', time() + 3600),
        ]);
        $client->request(Request::METHOD_GET, '/profile');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertSelectorTextContains('html', 'f9804988-e302-4788-8500-dd0822243cce');
        $this->assertSelectorTextContains('html', 'arnaud@email.com');
        $this->assertSelectorTextContains('html', 'Arnaud Picard');

    }
}
