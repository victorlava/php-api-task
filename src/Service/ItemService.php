<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    private $entityManager;

    private $itemRepository;

    public function __construct(ItemRepository $itemRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->itemRepository = $itemRepository;
    }

    public function get(int $id): ?Object
    {
        $item = $this->itemRepository->find($id);

        return !$item ? null : $item;
    }

    public function create(User $user, string $data)
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $item;
    }

    public function delete(Item $item): void
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($item);
        $manager->flush();
    }

    public function update(int $id, string $data): bool
    {
        $item = $this->itemRepository->find($id);

        if($item) {
            $item->setData($data);

            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}