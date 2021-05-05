<?php

namespace App\MessageHandler;

use App\Message\CreateItemMessage;
use App\Service\ItemService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateItemHandler implements MessageHandlerInterface
{
    public function __invoke(ItemService $itemService, CreateItemMessage $itemMessage)
    {
        $itemService->create($itemMessage->getUser(), $itemMessage->getData());
    }
}