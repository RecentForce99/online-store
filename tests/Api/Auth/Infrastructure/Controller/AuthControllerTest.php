<?php

declare(strict_types=1);

namespace App\Tests\Api\Auth\Infrastructure\Controller;

use App\Role\Domain\Entity\Role;
use App\Tests\Api\AbstractApiBaseTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\ByteString;

final class AuthControllerTest extends AbstractApiBaseTestCase
{
    private const string CONTROLLER_NAME = 'auth.signUp';

    public function setUp(): void
    {
        parent::setUp();

        $role = Role::create(
            'ROLE_USER',
            'Пользователь',
        );
        $this->entityManager->persist($role);
        $this->flusher->flush();
    }

    public function testSuccessSignUp(): void
    {
        $this->sendRequestByControllerName(self::CONTROLLER_NAME, [
            'name' => 'Less Grossman',
            'email' => ByteString::fromRandom(50)->toString() . '@test.com',
            'phone' => random_int(1000000000, 9999999999),
            'kladrId' => (string)random_int(1000000000, 9999999999999),
            'address' => 'New York',
            'password' => 'LKdkf291DSxz!?S',
        ]);

        $this->checkJsonableResponseByHttpCode(Response::HTTP_CREATED);
    }

    public function testFailedSignUpDueToWrongBody(): void
    {
        $this->sendRequestByControllerName(self::CONTROLLER_NAME, [
            'name' => '',
            'email' => '',
            'phone' => '',
            'password' => '',
        ]);

        $this->checkJsonableResponseByHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
