<?php

namespace App\Tests\Functional\Controller\Item;

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
        $this->client->request('PATCH', '/item?id=' . $id . '&data=' . $data);

        return $this;
    }

}
