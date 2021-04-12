<?php

namespace App\Tests\Functional\Controller\Item;

class ItemControllerUnauthorizedAccessTest extends ItemControllerTest
{

    public function testListReturns401ForUnauthorizedUser()
    {
        $this->getItems()->assertResponseStatusCodeSame(401);
    }

    public function testPatchReturns401ForUnauthorizedUser()
    {
        $this->updateItem(5, 'my message')->assertResponseStatusCodeSame(401);
    }

    public function testCreateReturns401ForUnauthorizedUser()
    {
        $this->createItem('my message')->assertResponseStatusCodeSame(401);
    }

    public function testDeleteReturns401ForUnauthorizedUser()
    {
        $this->deleteItem(5)->assertResponseStatusCodeSame(401);
    }

}
