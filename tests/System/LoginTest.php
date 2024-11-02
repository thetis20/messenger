<?php

namespace App\Tests\System;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testSuccessful()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form')->form([
            'username' => 'username',
            'password' => 'password'
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @dataProvider provideFormData
     * @param string $username
     * @param string $plainPassword
     * @param string $errorMessage
     * @return void
     */
    public function testFailed(string $username, string $plainPassword, string $errorMessage)
    {

        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/login');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form')->form([
            'username' => $username,
            'password' => $plainPassword
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();

        $this->assertSelectorTextContains('html', $errorMessage);
    }

    public function provideFormData(): \Generator
    {
        yield ['', 'password', 'Value "" is blank, but was expected to contain a value.'];
        yield ['username', '', 'Value "" is blank, but was expected to contain a value.'];
        yield ['username', 'bad-password', 'Wrong credentials !'];
        yield ['bad-username', 'password', 'User not found.'];
    }
}
