<?php

declare(strict_types=1);

namespace App\Tests\Web\Controller\Cart;

use Symfony\Component\HttpFoundation\Response;

final class CartProductListQueryTest extends AbstractBaseCartTestCaseAbstract
{
    private const string CART_PRODUCT_LIST_METHOD = 'GET';
    private const string CART_PRODUCT_LIST_ENDPOINT = '/api/cart';

    public function testSuccessCartProductList(): void
    {
        $this->client->request(
            self::CART_PRODUCT_LIST_METHOD,
            self::CART_PRODUCT_LIST_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }
}
