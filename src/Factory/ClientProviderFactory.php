<?php

namespace App\Service;

use App\Client\ClientProviderA;
use App\Client\ClientProviderB;
use App\Client\ClientProviderInterface;
use App\Dto\ClientProviderDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ClientProviderFactory
{
    private array $registry = [
        'provider_a' => [
            'namespace' => ClientProviderA::class,
            'url' => 'http://api1mock:8080/products/%s', // TODO: take from env. or use symfony autowiring parameters
        ],
        'provider_b' => [
            'namespace' => ClientProviderB::class,
            'url' => 'http://api2mock:8080/get_product?id=%s',
            'conversionRate' => 1.2
        ]
    ];

    public function create(string $marketplace): ClientProviderInterface
    {
        if(isset($this->registry[$marketplace])) {
            $settings = new ClientProviderDto($this->registry[$marketplace]);

            return new $this->registry[$marketplace]($settings);
        }

        throw new \Exception('Registry');
    }
}
