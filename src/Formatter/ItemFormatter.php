<?php

namespace App\Formatter;

class ItemFormatter implements TransformableInterface
{
    public static function transform(Object $object): array
    {
        return [
            'id' => $object->getId(),
            'data' => $object->getData(),
            'created_at' => $object->getCreatedAt(),
            'updated_at' => $object->getUpdatedAt()
        ];
    }
}