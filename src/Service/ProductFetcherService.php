<?php

namespace App\Service;

use App\Response\ClientProviderResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductFetcherService
{
    public function getSingle(
        Request $request,
        GenericProductProviderValidator $validator,
        ClientProviderFactory $factory,
    ): ClientProviderResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $validator->validate($data);
        $client = $factory->create($data['marketplace']);


        return $client->getProduct($data['item_id']);
    }
}
