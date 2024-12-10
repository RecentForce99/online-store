<?php

declare(strict_types=1);

namespace App\Tests\Api\Cart\Infrastructure\Controller;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Product\Domain\Entity\Product;
use App\Role\Domain\Entity\Role;
use App\Tests\Api\AbstractApiBaseTestCase;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

final class AddProductToCartTest extends AbstractApiBaseTestCase
{
    private const string CONTROLLER_NAME = 'cart.addProductToCart';
    private Product $product;

    /**
     * @throws GreaterThanMaxLengthException
     * @throws WrongLengthOfPhoneNumberException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
     */
    protected function setUp(): void
    {
        parent::setUp();


        $role = Role::create(
            'ROLE_USER',
            'Пользователь',
        );
        $this->entityManager->persist($role);



        $user = User::create(
            name: Name::fromString('Less Grossman'),
            email: Email::fromString('less-grossman@example.com'),
            phone: RuPhoneNumber::fromInt(1234567890),
            promoId: UuidV4::v4(),
            roles: new ArrayCollection([$role]),
        );
        $userPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($userPassword);
        $this->entityManager->persist($user);



        $this->product = Product::create(
            name: 'Product 1',
            weight: 10,
            height: 10,
            width: 10,
            length: 10,
            description: 'Description',
            cost: 100,
            tax: 0,
            version: 1,
        );
        $this->entityManager->persist($this->product);


        $this->entityManager->flush();

        $this->client->loginUser($user);
    }

    public function testSuccessAddProductToCart(): void
    {
        $this->sendRequestByControllerName(
            controllerName: self::CONTROLLER_NAME,
            routeParams: [
                'productId' => $this->product->getId()->toString(),
            ],
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testFailedAddProductToCartDueToWrongProductId(): void
    {
        $this->sendRequestByControllerName(
            controllerName: self::CONTROLLER_NAME,
            routeParams: [
                'productId' => UuidV4::v4()->toString(),
            ],
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