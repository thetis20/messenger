<?php

namespace App\Tests\Integration;

use App\Infrastructure\Test\IntegrationTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends IntegrationTestCase
{
    public function testSuccessful()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/registration');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form')->form([
            'registration[email]' => 'email@email.com',
            'registration[username]' => 'username',
            'registration[plainPassword][first]' => 'password',
            'registration[plainPassword][second]' => 'password',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @dataProvider provideFormData
     * @param string $email
     * @param string $username
     * @param array $plainPassword
     * @param string $errorMessage
     * @return void
     */
    public function testFailed(string $email, string $username, array $plainPassword, string $errorMessage)
    {

        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/registration');

        $this->assertResponseIsSuccessful();

        $form = $crawler->filter('form')->form([
            'registration[email]' => $email,
            'registration[username]' => $username,
            'registration[plainPassword][first]' => $plainPassword['first'],
            'registration[plainPassword][second]' => $plainPassword['second'],
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('html', $errorMessage);
    }
    public function provideFormData():\Generator
    {
        yield ['', 'username', ['first' => 'password', 'second' => 'password'], 'This value should not be blank.'];
        yield ['wrong-email.com', 'username', ['first' => 'password', 'second' => 'password'], 'This value is not a valid email address.'];
        yield ['used@email.com', 'username', ['first' => 'password', 'second' => 'password'], 'This email is already registered.'];
        yield ['email@email.com', '', ['first' => 'password', 'second' => 'password'], 'This value should not be blank.'];
        yield ['email@email.com', 'used-username', ['first' => 'password', 'second' => 'password'], 'This username is already registered.'];
        yield ['email@email.com', 'username', ['first' => '', 'second' => 'password'], 'The values do not match.'];
        yield ['email@email.com', 'username', ['first' => 'password', 'second' => ''], 'The values do not match.'];
        yield ['email@email.com', 'username', ['first' => 'password', 'second' => 'wrong-password'], 'The values do not match.'];
        yield ['email@email.com', 'username', ['first' => 'pass', 'second' => 'pass'], 'This value is too short. It should have 8 characters or more.'];
    }
}
