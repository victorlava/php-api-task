<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Item;
use App\Rule\ValidationRuleBuilder;
use App\Service\ItemService;
use App\Validator\BaseValidator;
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

    public function __construct(ValidationRuleBuilder $ruleBuilder)
    {
        $ruleBuilder->fields(['id', 'data'])->required();
        $ruleBuilder->field('id')->type('integer')->error('No id parameter', Response::HTTP_BAD_REQUEST);
        $ruleBuilder->field('data')->type('string')->error('No data parameter', Response::HTTP_BAD_REQUEST);

        $this->validator = new BaseValidator($ruleBuilder);
    }

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
    public function create(Request $request, ItemService $itemService, ValidationRuleBuilder $ruleBuilder): JsonResponse
    {
        $this->validator->builder->field('id')->disable();

        if(!$this->validator->isRequestValid($request)) {
            return $this->json( $this->validator->errorMessage(),
                                $this->validator->errorCode());
        }

        $itemService->create($this->getUser(), $request->get('data'));

        return $this->json([]);
    }

    /**
     * @Route("/item", name="item_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function update(Request $request, ItemService $itemService): JsonResponse
    {

        if(!$this->validator->isRequestValid($request)) {
            return $this->json( $this->validator->errorMessage(),
                                $this->validator->errorCode());
        }

        $id = $parameters['id'] ?? null;
        $data = $parameters['data'] ?? null;

        if(!$itemService->update((int) $id, $data)) {
            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, int $id, ItemService $itemService)
    {
        $this->validator->ruleBuilder->field('id')->disable();

        if(!$this->validator->isRequestValid($request)) {
            return $this->json( $this->validator->errorMessage(),
                                $this->validator->errorCode());
        }

        $item = $itemService->get($id);

        if ($item === null) {
            return $this->json(['error' => 'No item'], Response::HTTP_BAD_REQUEST);
        }

        $itemService->delete($item->id);

        return $this->json([]);
    }

}
