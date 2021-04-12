<?php

namespace App\Tests\Functional\Controller\Item;

class ItemControllerTest extends ItemControllerBaseTestCase
{
    public function testList()
    {
        $this->logIn()
            ->createItem('message1')
            ->createItem('message2')
            ->getItems();

        $this->assertResponseStatusCodeSame(200);
        $this->assertStringContainsString('message1', $this->client->getResponse()->getContent());
        $this->assertStringContainsString('message2', $this->client->getResponse()->getContent());
    }

    public function testCreate()
    {
        $messageToAssert = 'my message';

        $this->logIn()->createItem($messageToAssert)->getItems();

        $this->assertResponseStatusCodeSame(200);
        $this->assertStringContainsString($messageToAssert, $this->client->getResponse()->getContent());
    }

    public function testPatch() {}

    public function testDelete()
    {
        $this->logIn()->createItem('message')->getItems();
        $this->assertStringContainsString('message', $this->client->getResponse()->getContent());

        $item = $this->itemRepository->findOneBy(['data' => 'message']);

        $this->deleteItem($item->getId());

        $this->getItems()->assertSame('[]', $this->client->getResponse()->getContent());
    }
}
