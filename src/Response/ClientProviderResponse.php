<?php

namespace App\Response;

class ClientProviderResponse
{
    public function __construct(int $id, string $name, float $price)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => [
                'amount' => $this->price,
                'currency' => 'USD',
            ],
        ];
    }
}
