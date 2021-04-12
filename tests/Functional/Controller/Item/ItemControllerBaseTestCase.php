<?php

namespace App\Tests\Functional\Controller\Item;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DataFixtures\UserFixture;
use Doctrine\ORM\Tools\SchemaTool;

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

        $this->userRepository = static::$container->get(UserRepository::class);
        $this->itemRepository = static::$container->get(ItemRepository::class);
        $this->entityManager = static::$container->get(EntityManagerInterface::class);

        $this->user = $this->userRepository->findOneByUsername('john');
    }

    protected function tearDown(): void
    {

        $em = static::$container->get('doctrine')->getManager();
        $metaData = $em->getMetadataFactory()->getAllMetadata();

        $tool = new SchemaTool($em);
        $tool->dropSchema($metaData);
        $tool->createSchema($metaData);

        $userFixture = static::$container->get(UserFixture::class);
        $userFixture->load($em);

        parent::tearDown();
    }
}