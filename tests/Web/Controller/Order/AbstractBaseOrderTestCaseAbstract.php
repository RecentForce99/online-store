<?php

declare(strict_types=1);

namespace App\Tests\Web\Controller\Order;

use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Order\Domain\Repository\DeliveryTypeRepositoryInterface;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Order\Domain\Repository\OrderStatusRepositoryInterface;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\Tests\Fixture\Order\CreateDeliveryTypesFixture;
use App\Tests\Fixture\Order\CreateOrderStatusesFixture;
use App\Tests\Fixture\Product\CreateProductsFixture;
use App\Tests\Fixture\Role\CreateRolesFixture;
use App\Tests\Fixture\User\CreateUserFixture;
use App\Tests\Fixture\UserExample;
use App\Tests\Web\AbstractWebBaseTestCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Common\DataFixtures\Loader;

class AbstractBaseOrderTestCaseAbstract extends AbstractWebBaseTestCase
{
    protected RoleRepositoryInterface $roleRepository;
    protected UserRepositoryInterface $userRepository;
    protected ProductRepositoryInterface $productRepository;
    protected CartProductRepositoryInterface $cartProductRepository;
    protected User $user;
    private DeliveryTypeRepositoryInterface $deliveryTypeRepository;
    private OrderStatusRepositoryInterface $orderStatusRepository;
    private OrderRepositoryInterface $orderRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->logIn();
    }

    protected function injectDependencies(): void
    {
        parent::injectDependencies();
        $this->roleRepository = $this->client->getContainer()->get(RoleRepositoryInterface::class);
        $this->userRepository = $this->client->getContainer()->get(UserRepositoryInterface::class);
        $this->productRepository = $this->client->getContainer()->get(ProductRepositoryInterface::class);
        $this->cartProductRepository = $this->client->getContainer()->get(CartProductRepositoryInterface::class);
        $this->deliveryTypeRepository = $this->client->getContainer()->get(DeliveryTypeRepositoryInterface::class);
        $this->orderStatusRepository = $this->client->getContainer()->get(OrderStatusRepositoryInterface::class);
        $this->orderRepository = $this->client->getContainer()->get(OrderRepositoryInterface::class);
    }

    private function logIn(): void
    {
        $this->user = $this->userRepository->findByEmail((new UserExample())->getEmail());
        $this->client->loginUser($this->user);
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
        $fixtureLoader->addFixture(new CreateDeliveryTypesFixture(
            $this->deliveryTypeRepository,
            $this->flusher,
        ));
        $fixtureLoader->addFixture(new CreateOrderStatusesFixture(
            $this->orderStatusRepository,
            $this->flusher,
        ));

        return $fixtureLoader->getFixtures();
    }
}
