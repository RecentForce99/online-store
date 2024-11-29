<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Common\Domain\Repository\FlusherInterface;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\Tests\Fixture\Role\CreateRolesFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;

class WebBaseTestCase extends WebTestCase
{
    public const string CONTENT_TYPE = 'application/json';

    protected KernelBrowser $client;
    protected ?EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $passwordHasher;
    protected SerializerInterface $serializer;
    protected FlusherInterface $flusher;
    protected RoleRepositoryInterface $roleRepository;

    protected function setUp(): void
    {
        $client = static::createClient();
        $this->injectDependencies($client);

        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        }

        $fixtureLoader = new Loader();
        $fixtureLoader->addFixture(new CreateRolesFixture(
            $this->flusher,
            $this->roleRepository,
        ));

        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->entityManager, $purger);
        $executor->execute($fixtureLoader->getFixtures());
    }

    private function injectDependencies(KernelBrowser $client): void
    {
        $this->client = $client;
        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
        $this->passwordHasher = $client->getContainer()->get(UserPasswordHasherInterface::class);
        $this->serializer = $client->getContainer()->get(SerializerInterface::class);
        $this->flusher = $client->getContainer()->get(FlusherInterface::class);
        $this->roleRepository = $client->getContainer()->get(RoleRepositoryInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->clear();
        $this->entityManager->close();
    }
}