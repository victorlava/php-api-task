<?php

namespace App\Client;

use App\Response\ClientProviderResponse;

class ClientProviderB implements ClientProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly ClientProviderDto $settings,
    )
    {
    }

    public function getProduct(int $id): ClientProviderResponse
    {
        $dto = $this->settings;
        $response = $this->client->request('GET', sprintf($dto->getUrl(), $id);
        $body = json_decode($response->getContent(), 'json');

        return new ClientProviderResponse(
            $body['id'],
            $body['name'],
            $body['price'],
        );
    }
}
