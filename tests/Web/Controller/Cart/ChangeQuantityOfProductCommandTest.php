<?php

declare(strict_types=1);

namespace App\Tests\Web\Controller\Cart;

use App\Cart\Domain\Entity\CartProduct;
use App\Product\Domain\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

final class ChangeQuantityOfProductCommandTest extends AbstractBaseCartTestCaseAbstract
{
    private const string CHANGE_QUANTITY_OF_PRODUCT_METHOD = 'PATCH';
    private const string CHANGE_QUANTITY_OF_PRODUCT_ENDPOINT = '/api/cart';

    public function testSuccessChangeQuantityOfProduct(): void
    {
        /* @var Product $firstProduct */
        $firstProduct = current($this->productRepository->findAll());

        $cartProduct = CartProduct::create($this->user, $firstProduct);
        $this->cartProductRepository->add($cartProduct);
        $this->flusher->flush();

        $body = [
            'quantity' => 2,
        ];

        $this->client->request(
            self::CHANGE_QUANTITY_OF_PRODUCT_METHOD,
            self::CHANGE_QUANTITY_OF_PRODUCT_ENDPOINT . '/' . $firstProduct->getId()->toString(),
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
            $this->serializer->serialize($body, 'json'),
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testFailedChangeQuantityOfProduct(): void
    {
        /* @var Product $firstProduct */
        $firstProduct = current($this->productRepository->findAll());

        $wrongBody = [
            'quantity' => -1,
        ];

        $this->client->request(
            self::CHANGE_QUANTITY_OF_PRODUCT_METHOD,
            self::CHANGE_QUANTITY_OF_PRODUCT_ENDPOINT . '/' . $firstProduct->getId()->toString(),
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
