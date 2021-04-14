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

    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $data;

    public function setUp(): void
    {
        /** @var EntityManagerInterface */
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemRepository = $this->createMock(ItemRepository::class);
        $this->itemService = new ItemService($this->itemRepository, $this->entityManager);

        $this->user = $this->createMock(User::class);
        $this->data = 'message';
    }

    public function testCreate(): void
    {
        /** @var Item */
        $expectedObject = $this->mockItem($this->user, $this->data, false);

        $this->entityManager->expects($this->once())->method('persist')->with($expectedObject);
        $this->entityManager->expects($this->once())->method('flush');

        $this->itemService->create($this->user, $this->data);
    }

    public function testGet(): void
    {
        /** @var Item */
        $item = $this->mockItem($this->user, $this->data);

        $this->itemRepository->expects($this->once())->method('find')->with($item->getId());

        $item = $this->itemService->get($item->getId());

        $this->assertEquals(null, $item);
    }

    public function testUpdate(): void
    {
        /** @var Item */
        $item = $this->mockItem($this->user, $this->data);

        $this->itemRepository->expects($this->once())->method('find')->with($item->getId());

        $item = $this->itemService->update($item->getId(), $this->data);

        $this->assertEquals(false, $item);
    }

    private function mockItem(User $user, string $data, bool $shouldSetId = true): Item
    {
        /** @var Item */
        $item = new Item();
        if($shouldSetId) {
            $item->setId(1);
        }
        $item->setUser($user);
        $item->setData($data);

        return $item;
    }
}
