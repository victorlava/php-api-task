<?php

namespace App\Tests\Functional\Controller\Item;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\DataFixtures\UserFixture;

class ItemControllerBaseTestCase extends WebTestCase
{
    use ItemControllerActionTrait;

    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ItemRepository
     */
    protected $itemRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var object
     */
    protected $user;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = static::$container->get(EntityManagerInterface::class);
        $this->userRepository = static::$container->get(UserRepository::class);
        $this->itemRepository = static::$container->get(ItemRepository::class);

        $userFixture = static::$container->get(UserFixture::class);
        $userFixture->load($this->entityManager);

        $this->user = $this->userRepository->findOneByUsername('john');
    }

    protected function tearDown(): void
    {
        //TODO: to optimize this use TRUNCATE, or move to SQLite DB for tests
        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();

        $tool = new SchemaTool($this->entityManager);
        $tool->dropSchema($metaData);
        $tool->createSchema($metaData);

        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}