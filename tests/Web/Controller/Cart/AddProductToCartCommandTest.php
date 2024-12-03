<?php

declare(strict_types=1);

namespace App\Tests\Web\Controller\Cart;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

final class AddProductToCartCommandTest extends AbstractBaseCartTestCaseAbstract
{
    private const string ADD_PRODUCT_TO_CART_METHOD = 'POST';
    private const string ADD_PRODUCT_TO_CART_ENDPOINT = '/api/cart';

    public function testSuccessAddProductToCart(): void
    {
        /* @var UuidV4 $firstProductId */
        $firstProductId = current($this->productRepository->findAll())->getId()->toString();

        $this->client->request(
            self::ADD_PRODUCT_TO_CART_METHOD,
            self::ADD_PRODUCT_TO_CART_ENDPOINT . '/' . $firstProductId,
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
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
