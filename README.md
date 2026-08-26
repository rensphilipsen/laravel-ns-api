# Laravel NS API

Laravel wrapper for Nederlandse Spoorwegen APIs.

## Compatibility

- PHP: `^8.1` (including PHP 8.5)
- Laravel / Illuminate Support: `^10.0 || ^11.0 || ^12.0 || ^13.0`
- Guzzle: `^7.8 || ^8.0`

## Installation

Install via Composer:

```bash
composer require rensphilipsen/laravel-ns-api
```

## Configuration

Publish the configuration:

```bash
php artisan vendor:publish --provider="RensPhilipsen\NSApi\NSApiServiceProvider" --tag="config"
```

Environment variables:

- `NS_SUBSCRIPTION_KEY`
- `NS_API_URL` (default: `https://gateway.apiportal.ns.nl/reisinformatie-api/api/v2/`)
- `NS_DISRUPTIONS_API_URL` (default: `https://gateway.apiportal.ns.nl/disruptions`)
- `NS_API_CONNECT_TIMEOUT` (default: `10`)
- `NS_API_TIMEOUT` (default: `30`)

## Usage

```php
use RensPhilipsen\NSApi\NSApi;

$api = new NSApi();
```

### Stations

```php
$stations = $api->getStations(32);
```

### Departures by station code

```php
$departures = $api->getDeparturesByStationCode('ut', 32);
```

### Arrivals by station code

```php
$arrivals = $api->getArrivalsByStationCode('ut', 32);
```

### Disruptions API (v3)

```php
// List disruptions/maintenance/calamities
$disruptions = $api->getDisruptions([
    'type' => 'disruption',
    'isActive' => true,
], 'nl');

// Fetch one disruption by type and id
$singleDisruption = $api->getDisruption('disruption', 'disruption-id', 'en');

// Fetch disruptions for a station
$stationDisruptions = $api->getDisruptionsByStationCode('ut', 'en');
```

### Additional HTTP helpers

```php
$api->post('some-endpoint', ['field' => 'value']);
$api->postJson('some-endpoint', ['field' => 'value']);
$api->postMultipart('upload', [
    ['name' => 'file', 'contents' => fopen('/path/to/file', 'r')],
]);
```

## Error handling

Non-2xx responses are mapped to package exceptions where applicable:

- `404` => `NotFoundException`
- `422` => `ValidationException`
- `400` and `429` => `FailedActionException`
- Connection failures => `TimeoutException`

Other HTTP failures throw `Exception`.

## Package maintenance

This package includes GitHub automation for common package checks and releases:

- CI workflow: tests matrix on PHP 8.4/8.5 with Guzzle 7 and 8 combinations.
- Integrity workflow checks: `composer validate --strict` and `composer audit`.
- Static analysis: `composer analyse` (PHPStan).
- Coverage workflow: `composer test:coverage`, artifact upload, and optional Codecov upload.
- Dependabot updates for Composer dependencies and GitHub Actions.
- Release workflow on version tags (`*.*.*` and `v*.*.*`) that runs tests, creates a GitHub Release, and publishes a provenance attestation.

### Releasing a new version

1. Update `CHANGELOG.md` with the release notes.
2. Commit and merge to your release branch.
3. Create and push a SemVer tag, for example `1.2.0`.
4. The release workflow will run tests and publish the GitHub Release automatically.

### Signed tags

If your organization requires signed tags, create and push an annotated signed tag:

```bash
git tag -s 1.2.0 -m "Release 1.2.0"
git push origin 1.2.0
```
