<?php

declare(strict_types=1);

namespace App\Tests\Api\Cart\Infrastructure\Controller;

use App\Cart\Application\Exception\ProductWasNotAddedToCartException;
use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\GreaterThanMaxValueException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\LessThanMinValueException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Product\Domain\Entity\Product;
use App\Role\Domain\Entity\Role;
use App\Tests\Api\AbstractApiBaseTestCase;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Delivery;
use App\User\Domain\ValueObject\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

final class ChangeQuantityOfProductTest extends AbstractApiBaseTestCase
{
    private const string CONTROLLER_NAME = 'cart.changeQuantityOfProduct';
    private const int INITIAL_QUANTITY = 1;
    private Product $product;
    private User $user;

    /**
     * @throws ProductAlreadyAddedToCartException
     * @throws GreaterThanMaxLengthException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
     * @throws GreaterThanMaxValueException
     * @throws WrongLengthOfPhoneNumberException
     * @throws LessThanMinValueException
     */
    protected function setUp(): void
    {
        parent::setUp();


        $role = Role::create(
            'ROLE_USER',
            'Пользователь',
        );
        $this->entityManager->persist($role);



        $this->user = User::create(
            name: Name::fromString('Less Grossman'),
            email: Email::fromString('less-grossman@example.com'),
            phone: RuPhoneNumber::fromInt(1234567890),
            promoId: UuidV4::v4(),
            delivery: Delivery::create('New York', '999999999999999'),
            roles: new ArrayCollection([$role]),
        );
        $userPassword = $this->passwordHasher->hashPassword($this->user, 'password');
        $this->user->setPassword($userPassword);
        $this->entityManager->persist($this->user);



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



        $this->user->addProductToCart($this->product, self::INITIAL_QUANTITY);


        $this->flusher->flush();

        $this->client->loginUser($this->user);
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function testSuccessChangeQuantityOfProduct(): void
    {
        $quantity = 2;
        $this->sendRequestByControllerName(
            self::CONTROLLER_NAME,
            [
                'quantity' => $quantity,
            ],
            [
                'productId' => $this->product->getId()->toString(),
            ],
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
        $this->assertEquals(
            $this->user->getCartProductByProduct($this->product)->getQuantity(),
            $quantity
        );
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function testFailedChangeQuantityOfProductDueToWrongProductId(): void
    {
        $quantity = 2;
        $this->sendRequestByControllerName(
            self::CONTROLLER_NAME,
            [
                'quantity' => $quantity,
            ],
            [
                'productId' => UuidV4::v4()->toString(),
            ],
        );

        $responseJson = $this->client->getResponse()->getContent();
        $responseData = json_decode($responseJson, true);

        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $this->client->getResponse()->getStatusCode(),
        );
        $this->assertJson($responseJson);
        $this->assertArrayHasKey('code', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals(
            self::INITIAL_QUANTITY,
            $this->user->getCartProductByProduct($this->product)->getQuantity()
        );
    }

    /**
     * @throws ProductWasNotAddedToCartException
     */
    public function testFailedChangeQuantityOfProductDueToWrongQuantity(): void
    {
        $wrongQuantity = -1;
        $this->sendRequestByControllerName(
            self::CONTROLLER_NAME,
            [
                'quantity' => $wrongQuantity,
            ],
            [
                'productId' => $this->product->getId()->toString(),
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
        $this->assertEquals(
            self::INITIAL_QUANTITY,
            $this->user->getCartProductByProduct($this->product)->getQuantity()
        );
    }
}
