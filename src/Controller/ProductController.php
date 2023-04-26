<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductController extends AbstractController
{
    #[Route('/get_product_data', name: 'productData', methods: ['POST'])]
    public function productData(Request $request, HttpClientInterface $client): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['marketplace']) || !in_array($data['marketplace'],['provider_a', 'provider_b'])) {
            return $this->json(['error' => 'Correct marketplace id should be set'], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['item_id']) || !is_numeric($data['item_id'])) {
            return $this->json(['error' => 'Item ID should be set and be numeric'], Response::HTTP_BAD_REQUEST);
        }

        $productData = [];
        switch ($data['marketplace']) {
            case 'provider_a':
                $response = $client->request('GET', sprintf('http://api1mock:8080/products/%s', $data['item_id']));
                $body = json_decode($response->getContent(), 'json');
                $productData = [
                    'id' => $body['id'],
                    'name' => $body['productName'],
                    'price' => [
                        'amount' => $body['productPrice'],
                        'currency' => 'USD',
                    ],
                ];

                break;
            case 'provider_b':
                $response = $client->request('GET', sprintf('http://api2mock:8080/get_product?id=%s', $data['item_id']));
                $body = json_decode($response->getContent(), 'json');
                $productData = [
                    'id' => $body['id'],
                    'name' => $body['name'],
                    'price' => [
                        'amount' => $body['price'] * 1.2, //convert to USD
                        'currency' => 'USD',
                    ],
                ];

                break;
        }

        return $this->json($productData);
    }
}
