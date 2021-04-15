<?php

namespace App\Formatter;

interface TransformableInterface
{
    public static function transform(Object $object): array;
}