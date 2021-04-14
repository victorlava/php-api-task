<?php

namespace App\Tests\Unit;

use App\DataFixtures\UserFixture;
use App\Entity\Item;
use App\Entity\User;
use App\Service\ItemService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use App\Repository\ItemRepository;

class ItemServiceTest extends TestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    /**
     * @var ItemService
     */
    private $itemService;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function setUp(): void
    {
        /** @var EntityManagerInterface */
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemRepository = $this->createMock(ItemRepository::class);
        
        $this->itemService = new ItemService($this->itemRepository, $this->entityManager);
    }

    public function testCreate(): void
    {
        /** @var User */
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $expectedObject = new Item();
        $expectedObject->setUser($user);
        $expectedObject->setData($data);

        $this->entityManager->expects($this->once())->method('persist')->with($expectedObject);
        $this->entityManager->expects($this->once())->method('flush');

        $this->itemService->create($user, $data);
    }

    public function testGet(): void
    {

        $user = $this->createMock(User::class);
        $data = 'secret data';

        $item = new Item();
        $item->setId(1);
        $item->setUser($user);
        $item->setData($data);

        $this->itemRepository->expects($this->once())->method('find')->with($item->getId());

        $item = $this->itemService->get($item->getId());

        $this->assertEquals(null, $item);
    }

    public function testUpdate(): void
    {
        $user = $this->createMock(User::class);
        $data = 'secret data';

        $item = new Item();
        $item->setUser($user);
        $item->setData($data);
        $item->setId(1);

        $this->itemRepository->expects($this->once())->method('find')->with($item->getId());

        $item = $this->itemService->update($item->getId(), 'message');

        $this->assertEquals(false, $item);
    }
}
