<?php

declare(strict_types=1);

namespace App\Tests\Api;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiBaseTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $passwordHasher;
    protected SerializerInterface $serializer;
    protected DecoderInterface $decoder;
    protected RouterInterface $router;

    protected function setUp(): void
    {
        $this->injectDependencies();

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }
    }

    protected function injectDependencies(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->passwordHasher = $this->client->getContainer()->get(UserPasswordHasherInterface::class);
        $this->serializer = $this->client->getContainer()->get(SerializerInterface::class);
        $this->decoder = $this->client->getContainer()->get(DecoderInterface::class);
        $this->router = $this->client->getContainer()->get(RouterInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->clear();
        $this->entityManager->close();
    }

    protected function sendRequestByControllerName(
        string $controllerName,
        array $body = [],
        array $routeParams = [],
        array $getParams = [],
    ): void {
        $controller = $this->router->getRouteCollection()->get($controllerName);

        $path = $controller->getPath();
        $uri = static function () use ($path, $routeParams): string {
            foreach ($routeParams as $key => $value) {
                $path = str_replace('{' . $key . '}', $value, $path);
            }

            return $path;
        };

        $this->client->request(
            current($controller->getMethods()),
            $uri(),
            $getParams,
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $this->serializer->serialize($body, 'json'),
        );
    }
}
