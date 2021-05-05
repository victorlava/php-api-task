<?php

namespace App\Message;

class UpdateItemMessage
{
    private $id;

    private $data;

    public function __construct(int $id, string $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getData(): string
    {
        return $this->data;
    }
}