<?php

namespace RensPhilipsen\NSApi;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;

class NSApi
{
    use MakesHttpRequests,
        TransformsCollections,
        Actions\ManagesStations,
        Actions\ManagesDisruptions;

    /**
     * Client use to make requests
     */
    protected ClientInterface $guzzle;

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
     * Disruptions API Url of NS
     *
     * @var string
     */
    protected $disruptionsApiUrl;

    /**
     * NSApi constructor.
     */
    public function __construct(
        ?ClientInterface $client = null,
        ?string $apiKey = null,
        ?string $apiUrl = null,
        ?string $disruptionsApiUrl = null
    )
    {
        $this->apiKey = $apiKey ?? $this->readConfig('nsapi.api_key', '');
        $this->apiUrl = $apiUrl ?? $this->readConfig('nsapi.api_url', 'https://gateway.apiportal.ns.nl/reisinformatie-api/api/v2/');
        $this->disruptionsApiUrl = $disruptionsApiUrl ?? $this->readConfig('nsapi.disruptions_api_url', 'https://gateway.apiportal.ns.nl/disruptions');
        $this->guzzle = $client ?? $this->setupGuzzleClient();
    }

    protected function readConfig(string $key, $default = null)
    {
        if (function_exists('\\config')) {
            return \config($key, $default);
        }

        return $default;
    }

    /**
     * Setup the Guzzle Client
     */
    protected function setupGuzzleClient(): ClientInterface
    {
        $headers = [
            'Ocp-Apim-Subscription-Key' => $this->apiKey
        ];

        $options = [
            'base_uri' => $this->apiUrl,
            'headers'  => $headers,
            'http_errors' => false,
            'connect_timeout' => (float) $this->readConfig('nsapi.connect_timeout', 10),
            'timeout' => (float) $this->readConfig('nsapi.timeout', 30),
        ];

        return new GuzzleClient($options);
    }
}
