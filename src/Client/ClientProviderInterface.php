<?php

namespace App\Client;

use App\Response\ClientProviderResponse;

interface ClientProviderInterface
{
    public function getProduct(int $id): ClientProviderResponse;
}
