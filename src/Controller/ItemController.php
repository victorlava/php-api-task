<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Item;
use App\Service\ItemService;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function list(): JsonResponse
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findBy(['user' => $this->getUser()]);

        $allItems = [];
        foreach ($items as $item) {
            $oneItem['id'] = $item->getId();
            $oneItem['data'] = $item->getData();
            $oneItem['created_at'] = $item->getCreatedAt();
            $oneItem['updated_at'] = $item->getUpdatedAt();
            $allItems[] = $oneItem;
        }

        return $this->json($allItems);
    }

    /**
     * @Route("/item", name="item_create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, ItemService $itemService)
    {
        $data = $request->get('data');

        if (empty($data)) {
            return $this->json(['error' => 'No data parameter']);
        }

        $itemService->create($this->getUser(), $data);

        return $this->json([]);
    }

    /**
     * @Route("/item", name="item_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, ItemService $itemService): JsonResponse
    {
        $parameters = $request->toArray();

        $id = $parameters['id'] ?? null;
        $data = $parameters['data'] ?? null;

        if(empty((int) $id)) {
            return $this->json(['error' => 'No id parameter'], Response::HTTP_BAD_REQUEST);
        }

        if (empty($data)) {
            return $this->json(['error' => 'No data parameter'], Response::HTTP_BAD_REQUEST);
        }

        if(!$itemService->update((int) $id)) {
            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, int $id)
    {
        if (empty($id)) {
            return $this->json(['error' => 'No data parameter'], Response::HTTP_BAD_REQUEST);
        }

        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);

        if ($item === null) {
            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($item);
        $manager->flush();

        return $this->json([]);
    }
}
