<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MovieDBApiClient implements MovieDBApiClientInterface
{
    private $client;
    private $apiParams;

    public function __construct(HttpClientInterface $client, array $apiParams)
    {
        $this->client = $client;
        $this->apiParams = $apiParams;
    }

    public function requestApi(string $urlPath, array $params = []): mixed
    {
        $response = $this->client->request(
            Request::METHOD_GET,
            sprintf('%s/%s', $this->apiParams['base_url'], $urlPath),
            [
                'query' => array_merge($params, [
                    'api_key' => $this->apiParams['key'],
                    'language' => 'en-US'
                ])
            ]
        );

        if ($code = $response->getStatusCode() !== Response::HTTP_OK) {
            throw new \Exception($response->getContent(), $code);
        }
        
        return \json_decode($response->getContent(), true);
    }
}
