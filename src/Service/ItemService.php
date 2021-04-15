<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use App\Repository\ItemRepository;
use App\Formatter\ItemFormatter as ItemFormatter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ItemService
{
    /** @var ItemRepository  */
    private $itemRepository;

    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var LoggerInterface  */
    private $logger;

    public function __construct(ItemRepository $itemRepository, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->itemRepository = $itemRepository;
        $this->logger = $logger;
    }

    public function get(int $id): ?Object
    {
        $item = $this->itemRepository->find($id);

        $this->logger->info(__METHOD__ . ' ' . 'getting item');

        return !$item ? null : $item;
    }

    public function list(User $user): array
    {
        $items = $this->itemRepository->findBy(['user' => $user]);

        $itemCollection = [];
        foreach ($items as $item) {
            $itemCollection[] = ItemFormatter::transform($item);
        }

        $this->logger->info(__METHOD__ . ' ' . 'getting item list');

        return $itemCollection;
    }

    public function create(User $user, string $data)
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        $this->logger->info(__METHOD__ . ' ' . 'creating item');

        return $item;
    }

    public function delete(Item $item): void
    {
        $this->entityManager->remove($item);
        $this->entityManager->flush();

        $this->logger->info(__METHOD__ . ' ' . 'deleting item');
    }

    public function update(int $id, string $data): bool
    {
        $item = $this->itemRepository->find($id);

        if($item) {
            $item->setData($data);

            $this->entityManager->flush();

            $this->logger->info(__METHOD__ . ' ' . 'updating item');

            return true;
        }

        return false;
    }
}