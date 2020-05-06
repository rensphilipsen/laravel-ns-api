<?php

namespace RensPhilipsen\NSApi;

use GuzzleHttp\Client;

class NSApi
{

    /**
     * Client use to make requests
     *
     * @var Client
     */
    private Client $client;

    /**
     * NSApi constructor.
     */
    public function __construct()
    {
        $headers = [
            'Ocp-Apim-Subscription-Key' => config('nsapi.api_key')
        ];

        $options = [
            'base_uri' => config('nsapi.api_url'),
            'headers'  => $headers,
        ];

        $this->client = new Client($options);
    }

    private function request(string $uri, array $data): void
    {
        $this->client->get($uri);
    }

    public function stations(): void
    {
        
    }

}