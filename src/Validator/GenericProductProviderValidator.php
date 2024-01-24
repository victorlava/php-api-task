<?php

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GenericProductProviderValidator
{
    public function __construct(
        private readonly array $providers = []
    )
    {
        //['provider_a', 'provider_b']
    }

    public function validate(array $data): array
    {
        $errorBag = [];

        if (!isset($data['marketplace']) || !in_array($data['marketplace'], $this->providers)) {
            $errorBag[] = 'Correct marketplace id should be set';
//            return $this->json(['error' => 'Correct marketplace id should be set'], Response::HTTP_BAD_REQUEST);
        }

        if (!isset($data['item_id']) || !is_numeric($data['item_id'])) {
            $errorBag[] = 'Item ID should be set and be numeric';
//            return $this->json(['error' => 'Item ID should be set and be numeric'], Response::HTTP_BAD_REQUEST);
        }

        if ($errorBag !== []) {
             throw new ValidationException($errorBag);
        }

        return $data;
    }
}
