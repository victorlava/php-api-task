<?php

namespace App\Tests\Functional\Controller\Item;

class ItemControllerUnauthorizedAccessTest extends ItemControllerBaseTestCase
{

    const UNAUTHORIZED_ACCESS_STATUS_CODE = 401;

    public function testListReturns401ForUnauthorizedUser()
    {
        $this->getItems()->assertResponseStatusCodeSame(self::UNAUTHORIZED_ACCESS_STATUS_CODE);
    }

    public function testPatchReturns401ForUnauthorizedUser()
    {
        $this->updateItem(5, 'my message')->assertResponseStatusCodeSame(self::UNAUTHORIZED_ACCESS_STATUS_CODE);
    }

    public function testCreateReturns401ForUnauthorizedUser()
    {
        $this->createItem('my message')->assertResponseStatusCodeSame(self::UNAUTHORIZED_ACCESS_STATUS_CODE);
    }

    public function testDeleteReturns401ForUnauthorizedUser()
    {
        $this->deleteItem(5)->assertResponseStatusCodeSame(self::UNAUTHORIZED_ACCESS_STATUS_CODE);
    }

}
