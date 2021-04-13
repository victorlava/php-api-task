<?php

namespace App\Tests\Functional\Controller\Item;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use App\Tests\Model\Response\Item;

trait ItemControllerActionTrait
{

    public function logIn(): self
    {
        $this->client->loginUser($this->user);

        return $this;
    }

    public function getItems(): self
    {
        $this->client->request('GET', '/item');

        return $this;
    }

    public function deleteItem(int $id): self
    {
        $this->client->request('DELETE', '/item/' . $id);

        return $this;
    }

    public function createItem(string $data): self
    {
        $newItemData = ['data' => $data];

        $this->client->request('POST', '/item', $newItemData);

        return $this;
    }

    public function updateItem(int $id, string $data): self
    {
        $request = ['id' => $id, 'data' => $data];

        $this->client->request('PUT', '/item', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($request));

        return $this;
    }

}
