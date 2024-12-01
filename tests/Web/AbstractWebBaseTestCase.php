<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Common\Domain\Repository\FlusherInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractWebBaseTestCase extends WebTestCase
{
    public const string CONTENT_TYPE = 'application/json';
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $passwordHasher;
    protected SerializerInterface $serializer;
    protected FlusherInterface $flusher;

    protected function setUp(): void
    {
        $this->injectDependencies();

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }

        $ormPurger = new ORMPurger();
        $ormExecutor = new ORMExecutor($this->entityManager, $ormPurger);
        $ormExecutor->execute($this->getFixtures());
    }

    abstract protected function getFixtures(): array;

    protected function injectDependencies(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        $this->passwordHasher = $this->client->getContainer()->get(UserPasswordHasherInterface::class);
        $this->serializer = $this->client->getContainer()->get(SerializerInterface::class);
        $this->flusher = $this->client->getContainer()->get(FlusherInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->clear();
        $this->entityManager->close();
    }
}
