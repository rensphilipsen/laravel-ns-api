<?php

namespace RensPhilipsen\NSApi;

use GuzzleHttp\Client as GuzzleClient;

class NSApi
{
    use MakesHttpRequests,
        TransformsCollections,
        Actions\ManagesStations;

    /**
     * Client use to make requests
     */
    protected $guzzle;

    /**
     * API Key of NS
     *
     * @var string
     */
    protected $apiKey;

    /**
     * API Url of NS
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * NSApi constructor.
     */
    public function __construct()
    {
        $this->apiKey = config('nsapi.api_key');
        $this->apiUrl = config('nsapi.api_url');
        $this->setupGuzzleClient();
    }

    /**
     * Setup the Guzzle Client
     */
    protected function setupGuzzleClient()
    {
        $headers = [
            'Ocp-Apim-Subscription-Key' => $this->apiKey
        ];

        $options = [
            'base_uri' => $this->apiUrl,
            'headers'  => $headers,
        ];

        $this->guzzle = new GuzzleClient($options);
    }
}
