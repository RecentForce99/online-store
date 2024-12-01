<?php

declare(strict_types=1);

namespace App\Tests\Web\Controller\Cart;

use App\Cart\Domain\Entity\CartProduct;
use App\Product\Domain\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

final class DeleteProductFromCartCommandTest extends AbstractBaseCartTestCaseAbstract
{
    private const string DELETE_PRODUCT_FROM_CART_METHOD = 'DELETE';
    private const string DELETE_PRODUCT_FROM_CART_ENDPOINT = '/api/cart';

    public function testSuccessDeleteProductFromCart(): void
    {
        /* @var Product $firstProduct */
        $firstProduct = current($this->productRepository->findAll());

        $cartProduct = CartProduct::create($this->user, $firstProduct);
        $this->cartProductRepository->add($cartProduct);
        $this->flusher->flush();

        $this->client->request(
            self::DELETE_PRODUCT_FROM_CART_METHOD,
            self::DELETE_PRODUCT_FROM_CART_ENDPOINT . '/' . $firstProduct->getId()->toString(),
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testFailedDeleteProductFromCart(): void
    {
        $this->client->request(
            self::DELETE_PRODUCT_FROM_CART_METHOD,
            self::DELETE_PRODUCT_FROM_CART_ENDPOINT . '/' . UuidV4::v4()->toString(),
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
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
    }
}
