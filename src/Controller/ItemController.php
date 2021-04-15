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
    /** @var BaseValidator  */
    private $validator;

    public function __construct(ValidationRuleBuilder $rules)
    {
        $this->validator = new BaseValidator($this->setDefaultValidationRules($rules));
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
    public function create(Request $request, ItemService $itemService): JsonResponse
    {
        $this->validator->builder->field('id')->disable();
        $this->validator->builder->field('item')->disable();

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
    public function up  date(Request $request, ItemService $itemService): JsonResponse
    {

        $this->validator->builder->field('item')->disable();

        if(!$this->validator->isRequestValid($request)) {
            return $this->json( $this->validator->errorMessage(),
                                $this->validator->errorCode());
        }

        $item = $itemService->update($request->request->get('id'), $request->request->get('data'));

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, int $id, ItemService $itemService): JsonResponse
    {
        $this->validator->builder->field('data')->disable();

        $item = $itemService->get($id);

        $request->request->add(['id' => $id, 'item' => $item]);

        if(!$this->validator->isRequestValid($request)) {
            return $this->json( $this->validator->errorMessage(),
                                $this->validator->errorCode());
        }

        $itemService->delete($item);

        return $this->json([]);
    }

    private function setDefaultValidationRules(ValidationRuleBuilder $rules): ValidationRuleBuilder
    {
        $rules->fields(['id', 'data', 'item'])->required();
        $rules->field('id')->type('integer')->error('No id parameter', Response::HTTP_BAD_REQUEST);
        $rules->field('data')->type('string')->error('No data parameter', Response::HTTP_BAD_REQUEST);
        $rules->field('item')->type('object')->error('No item', Response::HTTP_BAD_REQUEST);

        return $rules;
    }

}
