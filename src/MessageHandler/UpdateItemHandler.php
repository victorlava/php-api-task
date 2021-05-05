<?php

namespace App\MessageHandler;

use App\Message\UpdateItemMessage;
use App\Service\ItemService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UpdateItemHandler implements MessageHandlerInterface
{
    public function __invoke(ItemService $itemService, UpdateItemMessage $itemMessage)
    {
        $itemService->update($itemMessage->getId(), $itemMessage->getData());
    }
}