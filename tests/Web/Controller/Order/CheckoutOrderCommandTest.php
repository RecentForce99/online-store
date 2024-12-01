<?php

declare(strict_types=1);

namespace App\Tests\Web\Controller\Order;

use App\Cart\Domain\Entity\CartProduct;
use App\Product\Domain\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

final class CheckoutOrderCommandTest extends AbstractBaseOrderTestCaseAbstract
{
    private const string CHECKOUT_ORDER_METHOD = 'POST';
    private const string CHECKOUT_ORDER_ENDPOINT = '/api/order';

    public function testSuccessCheckoutOrder(): void
    {
        /* @var Product $firstProduct */
        $firstProduct = current($this->productRepository->findAll());

        $cartProduct = CartProduct::create($this->user, $firstProduct);
        $this->cartProductRepository->add($cartProduct);
        $this->flusher->flush();

        $body = [
            'deliveryType' => 'pickup',
        ];

        $this->client->request(
            self::CHECKOUT_ORDER_METHOD,
            self::CHECKOUT_ORDER_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => parent::CONTENT_TYPE],
            $this->serializer->serialize($body, 'json'),
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testFailedCheckoutOrder(): void
    {
        $wrongBody = [
            'productId' => UuidV4::v4()->toString(),
        ];

        $this->client->request(
            self::CHECKOUT_ORDER_METHOD,
            self::CHECKOUT_ORDER_ENDPOINT,
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
