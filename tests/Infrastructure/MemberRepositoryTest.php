<?php

namespace App\Tests\Infrastructure;

use App\Infrastructure\Adapter\Repository\DiscussionRepository;
use App\Infrastructure\Adapter\Repository\MemberRepository;
use App\Infrastructure\Security\Dto\TokensBag;
use App\Infrastructure\Security\User;
use Messenger\Domain\Entity\Member;
use Messenger\Domain\Gateway\MemberGateway;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemberRepositoryTest extends KernelTestCase
{
    private MemberRepository $memberRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->memberRepository = $container->get(MemberGateway::class);
    }

    static public function data(): array
    {
        $members = [MemberRepository::parse([
            'email' => 'arnaud+memberRepository@mail.com',
            'userIdentifier' => 'arnaud+memberRepository',
            'username' => 'Arnaud'
        ])];
        return [
            'members' => $members
        ];
    }

    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        return parent::createClient(array_merge($options, ['environment' => 'test']), $server);
    }

    /**
     * @return void
     */
    public function testSaveUpdate()
    {
        $this->memberRepository->save(MemberRepository::parse([
            'email' => 'arnaud+memberRepository@mail.com',
            'userIdentifier' => 'arnaud+memberRepository',
            'username' => 'Arnaud Picard'
        ]));
        $user = $this->memberRepository->findOneByEmail('arnaud+memberRepository@mail.com');
        $this->assertEquals('Arnaud Picard', $user->getUsername());
    }

    /**
     * @return void
     */
    public function testSaveInsert()
    {
        $this->memberRepository->save(MemberRepository::parse([
            'email' => 'jean+memberRepository@mail.com',
            'userIdentifier' => 'jean+memberRepository',
            'username' => 'Jean Picard'
        ]));
        $user = $this->memberRepository->findOneByEmail('jean+memberRepository@mail.com');
        $this->assertEquals('Jean Picard', $user->getUsername());
    }
}
