<?php

namespace App\Tests\Functional\Controller\Item;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ItemControllerTest extends WebTestCase
{

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

    protected $user;

    public function setUp()
    {
        $this->client = static::createClient();

        $this->userRepository = static::$container->get(UserRepository::class);
        $this->itemRepository = static::$container->get(ItemRepository::class);
        $this->entityManager = static::$container->get(EntityManagerInterface::class);

        $this->user = $this->userRepository->findOneByUsername('john');
    }

    public function testList()
    {
        $this->logIn()
            ->createItem('message1')
            ->createItem('message2')
            ->getItems();

        $this->assertResponseStatusCodeSame(200);
        $this->assertStringContainsString('message1', $this->client->getResponse()->getContent());
        $this->assertStringContainsString('message2', $this->client->getResponse()->getContent());
    }

    public function testCreate()
    {

        $this->logIn()->createItem('my message')->getItems();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('my message', $this->client->getResponse()->getContent());
    }

    protected function logIn()
    {
        $this->client->loginUser($this->user);

        return $this;
    }

    protected function getItems()
    {
        $this->client->request('GET', '/item');

        return $this;
    }

    protected function deleteItem(int $id)
    {
        $this->client->request('DELETE', '/item/' . $id);

        return $this;
    }

    protected function createItem(string $data)
    {
        $newItemData = ['data' => $data];

        $this->client->request('POST', '/item', $newItemData);

        return $this;
    }

    protected function updateItem(int $id, string $data)
    {
        $this->client->request('PATCH', '/item?id=' . $id . '&data=' . $data);

        return $this;
    }
}
