<?php

namespace App\Service;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ServiceFile
{ 
    private Serializer $serializer;

    public function __construct(
        private string $token,
        private string $url_api,        
        private HttpClientInterface $httpClient,
    ){}

    public function convertDataFile($form): array
    { 
        $fileExtension = $form->get('file')->getData()->guessExtension();

        // Initialization Serializer           
        $normalizer = [new ObjectNormalizer];
        $encoders = [new CsvEncoder(), new JsonEncoder() ];

        $this->serializer = new Serializer($normalizer, $encoders);

        // Get content form data and convert to array
        $formData = $form->get('file')->getData()->getContent(); 
        $data = $this->serializer->decode($formData, $fileExtension);

        return $this->dataControl($data);       
    }

    public function dataControl($data): array
    {   
        // Array for required fields  
        $requiredFields = ['name', 'surname', 'city']; 

        // Verification data
        foreach ($data as $row) {

            $missingField = array_diff($requiredFields, array_keys($row));
        }

        // Process error
        if (!empty($missingField)) {

            $response['error'] = 'Missing or malformated fields !';
        
        } else {

            return $this->postRequestToApi($data);
        }
        
        return $response;
    }

    public function postRequestToApi($data): array
    {   
        // The API accepts a body with 2 attributes : 'data' and 'token'    
        $body = [
            'token' => $this->token,
            'data' => $data,    
        ];

        // Convert body en JSON
        $body = $this->serializer->serialize($body, 'json');

        // Array for API
        $opts = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ];

        // Request to API
        $response = $this->httpClient->request('POST', $this->url_api, $opts);
        
        return $response->toArray();
    }
}