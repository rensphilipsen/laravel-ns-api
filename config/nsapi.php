<?php

return [

    /**
     * API Url of the NS API
     */
    'api_url' => env('NS_API_URL', 'https://gateway.apiportal.ns.nl/reisinformatie-api/api/v2/'),

    /**
     * Disruptions API Url of NS
     */
    'disruptions_api_url' => env('NS_DISRUPTIONS_API_URL', 'https://gateway.apiportal.ns.nl/disruptions'),

    /**
     * Maximum time in seconds to wait while connecting.
     */
    'connect_timeout' => env('NS_API_CONNECT_TIMEOUT', 10),

    /**
     * Maximum total request time in seconds.
     */
    'timeout' => env('NS_API_TIMEOUT', 30),

    /**
     * API Key of the NS API
     */
    'api_key' => env('NS_SUBSCRIPTION_KEY', '')

];