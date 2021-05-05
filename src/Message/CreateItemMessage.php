<?php

namespace App\Message;

use App\Entity\User;

class CreateItemMessage
{
    private $data;

    private $user;

    public function __construct(User $user, string $data)
    {
        $this->user = $user;
        $this->data = $data;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getData(): string
    {
        return $this->data;
    }
}