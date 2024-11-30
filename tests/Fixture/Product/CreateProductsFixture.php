<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Product;

use App\Common\Infrastructure\Repository\Flusher;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CreateProductsFixture extends Fixture
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly Flusher                    $flusher,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        /* @var Product $product */
        foreach ($this->getProductsToCreate() as $product) {
            $this->productRepository->add($product);
        }

        $this->flusher->flush();
    }

    private function getProductsToCreate(): array
    {
        return [
            Product::create(
                name: 'Product1',
                weight: 100,
                height: 100,
                width: 100,
                length: 100,
                description: 'Description 1',
                cost: 1000,
                tax: 10,
                version: 1
            ),
            Product::create(
                name: 'Product2',
                weight: 200,
                height: 200,
                width: 200,
                length: 200,
                description: 'Description 2',
                cost: 2000,
                tax: 20,
                version: 2
            ),
            Product::create(
                name: 'Product2',
                weight: 300,
                height: 300,
                width: 300,
                length: 300,
                description: 'Description 2',
                cost: 3000,
                tax: 30,
                version: 3
            ),
        ];
    }
}
