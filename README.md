# Laravel NS API

This package is a wrapper for the Nederlandse Spoorwegen (NS) API.

## Installation

Install via composer

```
composer require rensphilipsen/laravel-ns-api
```

## Configuration

Publish the configuration by running

```
php artisan vendor:publish --provider="RensPhilipsen\NSApi\NSApiServiceProvider" --tag="config"
```

## Examples

### Get all stations

To fetch all the NS stations:

```
    $api = new NSApi();
    $api->getStations();
```

### Get departures by station code

```
    $api = new NSApi();

    // The first parameter is the stations `code`.
    // The second parameter is the amount of departures/journeys. Defaults to 32.
    $api->getDeparturesByStationCode('ut', 32)
```

### Get arrivals by station code

```
    $api = new NSApi();

    // The first parameter is the stations `code`.
    // The second parameter is the amount of arrivals/journeys. Defaults to 32.
    $api->getArrivalsByStationCode('ut', 32)
```
