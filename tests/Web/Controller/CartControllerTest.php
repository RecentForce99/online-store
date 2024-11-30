<?php

namespace App\Tests\Web\Controller;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\Tests\Fixture\Product\CreateProductsFixture;
use App\Tests\Fixture\Role\CreateRolesFixture;
use App\Tests\Fixture\User\CreateUserFixture;
use App\Tests\Fixture\UserExample;
use App\Tests\Web\WebBaseTestCase;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

final class CartControllerTest extends WebBaseTestCase
{
    private const string ADD_PRODUCT_TO_CART_METHOD = 'POST';
    private const string ADD_PRODUCT_TO_CART_ENDPOINT = '/api/cart';

    private RoleRepositoryInterface $roleRepository;
    private UserRepositoryInterface $userRepository;
    private ProductRepositoryInterface $productRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->logIn();
    }

    private function logIn(): void
    {
        $userExample = new UserExample();
        $user = $this->userRepository->findByEmail($userExample->getEmail());

        $this->client->loginUser($user);
    }

    protected function injectDependencies(): void
    {
        parent::injectDependencies();
        $this->roleRepository = $this->client->getContainer()->get(RoleRepositoryInterface::class);
        $this->userRepository = $this->client->getContainer()->get(UserRepositoryInterface::class);
        $this->productRepository = $this->client->getContainer()->get(ProductRepositoryInterface::class);
    }

    protected function getFixtures(): array
    {
        $fixtureLoader = new Loader();
        $fixtureLoader->addFixture(new CreateRolesFixture(
            $this->roleRepository,
            $this->flusher,
        ));
        $fixtureLoader->addFixture(new CreateUserFixture(
            $this->roleRepository,
            $this->userRepository,
            $this->passwordHasher,
            $this->flusher,
        ));
        $fixtureLoader->addFixture(new CreateProductsFixture(
            $this->productRepository,
            $this->flusher,
        ));

        return $fixtureLoader->getFixtures();
    }

    public function testSuccessAddProductToCart(): void
    {
        /* @var UuidV4 $firstProductId */
        $firstProductId = $this->productRepository->findAll()[0]->getId();

        $body = [
            'productId' => $firstProductId->toString(),
        ];

        $this->client->request(
            self::ADD_PRODUCT_TO_CART_METHOD,
            self::ADD_PRODUCT_TO_CART_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
            $this->serializer->serialize($body, 'json'),
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testFailedAddProductToCart(): void
    {
        $wrongBody = [
            'productId' => UuidV4::v4()->toString(),
        ];

        $this->client->request(
            self::ADD_PRODUCT_TO_CART_METHOD,
            self::ADD_PRODUCT_TO_CART_ENDPOINT,
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