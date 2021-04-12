<?php

namespace App\Tests;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ItemControllerTest extends WebTestCase
{

    private $client;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $user;

    public function setUp()
    {
        $this->client = static::createClient();

        $this->userRepository = static::$container->get(UserRepository::class);
        $this->itemRepository = static::$container->get(ItemRepository::class);
        $this->entityManager = static::$container->get(EntityManagerInterface::class);

        $this->user = $this->userRepository->findOneByUsername('john');
    }

    public function testListReturns401ForUnauthorizedUser()
    {
        $this->getItems()->assertResponseStatusCodeSame(401);
    }

    public function testCreateReturns401ForUnauthorizedUser()
    {
        $this->createItem('very secure new item data')->assertResponseStatusCodeSame(401);
    }

    public function testDeleteReturns401ForUnauthorizedUser()
    {
        $this->deleteItem(5)->assertResponseStatusCodeSame(401);
    }

    public function testListReturns200ForAuthorizedUser()
    {
        $this->logIn()->createItem('very secure new item data')->getItems();

        $item = $this->itemRepository->findOneBy(['data' => 'very secure new item data']);

        $this->assertResponseStatusCodeSame(200);
//        $this->assertEquals($this->client->getResponse()->getContent(), new JsonResponse($item));
    }


    public function testCreate()
    {

        $this->logIn()->createItem('very secure new item data')->getItems();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('very secure new item data', $this->client->getResponse()->getContent());
    }

    private function logIn()
    {
        $this->client->loginUser($this->user);

        return $this;
    }

    private function getItems()
    {
        $this->client->request('GET', '/item');

        return $this;
    }

    private function deleteItem(int $id)
    {
        $this->client->request('DELETE', '/item/' . $id);

        return $this;
    }

    private function createItem(string $data)
    {
        $newItemData = ['data' => $data];

        $this->client->request('POST', '/item', $newItemData);
        
        return $this;
    }
}
