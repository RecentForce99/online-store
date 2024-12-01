<?php

declare(strict_types=1);

namespace App\Tests\Web\Controller;

use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\Tests\Fixture\Role\CreateRolesFixture;
use App\Tests\Web\AbstractWebBaseTestCase;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\ByteString;

final class AuthControllerTestAbstract extends AbstractWebBaseTestCase
{
    private const string SIGN_UP_METHOD = 'POST';
    private const string SIGN_UP_ENDPOINT = '/api/auth/signUp';
    private RoleRepositoryInterface $roleRepository;

    protected function injectDependencies(): void
    {
        parent::injectDependencies();
        $this->roleRepository = $this->client->getContainer()->get(RoleRepositoryInterface::class);
    }

    protected function getFixtures(): array
    {
        $fixtureLoader = new Loader();
        $fixtureLoader->addFixture(new CreateRolesFixture(
            $this->roleRepository,
            $this->flusher,
        ));

        return $fixtureLoader->getFixtures();
    }

    public function testSuccessSignUp(): void
    {
        $body = [
            'name' => 'Less Grossman',
            'email' => ByteString::fromRandom(50)->toString() . '@test.com',
            'phone' => random_int(1000000000, 9999999999),
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
        $wrongBody = [
            'name' => '',
            'email' => '',
            'phone' => '',
            'password' => '',
        ];

        $this->client->request(
            self::SIGN_UP_METHOD,
            self::SIGN_UP_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
            $this->serializer->serialize($wrongBody, 'json'),
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
