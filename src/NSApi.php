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

    /**
     * Do a request to the API
     *
     * @param string $uri
     * @param array $params
     * @return mixed
     */
    private function request(string $uri, array $params = [])
    {
        $response = $this->client->request('GET', $uri, ['query' => $params]);
        return json_decode($response->getBody()->getContents());
    }

    /**
     * Retrieve all stations
     *
     * @return mixed
     */
    public function stations()
    {
        return $this->request('stations');
    }

}