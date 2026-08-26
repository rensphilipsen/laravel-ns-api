# Changelog
All notable changes to `laravel-ns-api` will be documented in this file

## 1.2.0 - 2026-08-26
- Added compatibility with `guzzlehttp/guzzle:^8.0` while keeping support for `^7.8`.
- Added explicit support for PHP 8.5 and Laravel 13 (`illuminate/support:^13`).
- Improved HTTP handling:
	- Correctly maps non-2xx responses to package exceptions.
	- Converts connection failures to `TimeoutException`.
	- Returns full decoded response when the `payload` wrapper is not present.
	- Added `postJson()` and `postMultipart()` helper methods.
- Added disruptions API wrapper methods:
	- `getDisruptions()`
	- `getDisruption()`
	- `getDisruptionsByStationCode()`
- Added request timeout config options and disruptions API URL config.
- Fixed package alias registration to the correct facade class.
- Fixed namespace casing and resource hydration edge cases for newer PHP versions.
- Added PHPUnit test suite covering request methods, payload modes, error handling, and timeout behavior.

## 1.0.0 - 2020-05-05
Initial setup

## 1.0.1 - 2020-10-04
 - Add `Product` and `RouteStation` entities.
 - Update the name of the fields being used on the NS API for entity `Departure`.
 - Add new method `getDelay()` to calculate the actual delay of the departure.