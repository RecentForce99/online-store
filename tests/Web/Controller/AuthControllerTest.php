<?php

namespace App\Tests\Web\Controller;

use App\Tests\Web\WebBaseTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AuthControllerTest extends WebBaseTestCase
{
    private const string SIGN_UP_METHOD = 'POST';
    private const string SIGN_UP_ENDPOINT = '/api/auth/signUp';

    public function testSuccessSignUp(): void
    {
        $body = [
            'name' => 'Less Grossman',
            'email' => 'less-grossman@test.com',
            'phone' => 1234567890,
            'password' => 'LKdkf291DSxz!?S',
        ];

        $this->client->request(
            self::SIGN_UP_METHOD,
            self::SIGN_UP_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
            $this->serializer->serialize($body, 'json'),
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testFailedSignUp(): void
    {
        $body = [
            'name' => '',
            'email' => 'less-grossman@test.com',
            'phone' => '1234567890',
            'password' => 'LKdkf291DSxz!?S',
        ];

        $this->client->request(
            self::SIGN_UP_METHOD,
            self::SIGN_UP_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
            $this->serializer->serialize($body, 'json'),
        );

        $responseJson = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseJson, true);

        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->client->getResponse()->getStatusCode(),
        );
        $this->assertJson($responseJson);
        $this->assertArrayHasKey('code', $responseData);
        $this->assertArrayHasKey('message', $responseData);
    }
}