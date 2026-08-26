<?php

namespace RensPhilipsen\NSApi\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RensPhilipsen\NSApi\Exceptions\FailedActionException;
use RensPhilipsen\NSApi\Exceptions\NotFoundException;
use RensPhilipsen\NSApi\Exceptions\TimeoutException;
use RensPhilipsen\NSApi\Exceptions\ValidationException;
use RensPhilipsen\NSApi\NSApi;

class NSApiHttpCompatibilityTest extends TestCase
{
    public function test_it_performs_successful_get_request(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode(['payload' => ['ok' => true]])),
        ], $history);

        $result = $api->get('stations');

        $this->assertSame(['ok' => true], $result);
        $this->assertSame('GET', $history[0]['request']->getMethod());
        $this->assertSame('/reisinformatie-api/api/v2/stations', $history[0]['request']->getUri()->getPath());
    }

    public function test_it_sends_query_parameters(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode(['payload' => []])),
        ], $history);

        $api->get('departures', ['station' => 'ut', 'maxJourneys' => 40]);

        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        $this->assertSame('ut', $query['station']);
        $this->assertSame('40', $query['maxJourneys']);
    }

    public function test_it_sends_form_payloads_with_post(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode(['payload' => ['created' => true]])),
        ], $history);

        $result = $api->post('forms', ['foo' => 'bar']);

        $this->assertSame(['created' => true], $result);
        $this->assertStringContainsString('foo=bar', (string) $history[0]['request']->getBody());
        $this->assertSame('application/x-www-form-urlencoded', $history[0]['request']->getHeaderLine('Content-Type'));
    }

    public function test_it_sends_json_payloads_with_post_json(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode(['payload' => ['created' => true]])),
        ], $history);

        $api->postJson('json', ['foo' => 'bar']);

        $this->assertSame('application/json', $history[0]['request']->getHeaderLine('Content-Type'));
        $this->assertSame('{"foo":"bar"}', (string) $history[0]['request']->getBody());
    }

    public function test_it_sends_multipart_payloads(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode(['payload' => ['uploaded' => true]])),
        ], $history);

        $api->postMultipart('upload', [
            ['name' => 'field', 'contents' => 'value'],
        ]);

        $this->assertStringContainsString('multipart/form-data', $history[0]['request']->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('value', (string) $history[0]['request']->getBody());
    }

    public function test_it_sets_authentication_header(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode(['payload' => []])),
        ], $history, ['Ocp-Apim-Subscription-Key' => 'integration-test-key']);

        $api->get('stations');

        $this->assertSame('integration-test-key', $history[0]['request']->getHeaderLine('Ocp-Apim-Subscription-Key'));
    }

    public function test_it_throws_not_found_for_404(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(404, [], json_encode(['message' => 'missing'])),
        ], $history);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('missing');

        $api->get('stations');
    }

    public function test_it_throws_validation_exception_for_422(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(422, [], json_encode(['errors' => ['station' => ['invalid']]])),
        ], $history);

        try {
            $api->get('stations');
            $this->fail('Expected ValidationException was not thrown.');
        } catch (ValidationException $exception) {
            $this->assertSame(['station' => ['invalid']], $exception->errors());
        }
    }

    public function test_it_throws_failed_action_exception_for_400(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(400, [], json_encode(['message' => 'bad request'])),
        ], $history);

        $this->expectException(FailedActionException::class);
        $this->expectExceptionMessage('bad request');

        $api->get('stations');
    }

    public function test_it_converts_connect_exceptions_to_timeout_exception(): void
    {
        $history = [];
        $mock = new MockHandler([
            new ConnectException('Connection timeout', new Request('GET', 'stations')),
        ]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        $client = new Client([
            'base_uri' => 'https://gateway.apiportal.ns.nl/reisinformatie-api/api/v2/',
            'handler' => $stack,
            'http_errors' => true,
            'headers' => ['Ocp-Apim-Subscription-Key' => 'test-key'],
        ]);

        $api = new NSApi($client, 'test-key');

        $this->expectException(TimeoutException::class);
        $this->expectExceptionMessage('Script timed out while waiting for the process to complete.');

        $api->get('stations');
    }

    public function test_it_returns_decoded_body_when_payload_key_is_missing(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode([
                ['id' => 'disruption-1', 'type' => 'DISRUPTION'],
            ])),
        ], $history);

        $result = $api->get('disruptions');

        $this->assertSame([
            ['id' => 'disruption-1', 'type' => 'DISRUPTION'],
        ], $result);
    }

    public function test_it_calls_disruptions_station_endpoint_with_language_header(): void
    {
        $history = [];
        $api = $this->makeApi([
            new Response(200, [], json_encode(['payload' => []])),
        ], $history);

        $api->getDisruptionsByStationCode('ut', 'en');

        $this->assertSame('https://gateway.apiportal.ns.nl/disruptions/v3/station/ut', (string) $history[0]['request']->getUri());
        $this->assertSame('en', $history[0]['request']->getHeaderLine('Accept-Language'));
    }

    private function makeApi(array $responses, &$history, array $headers = []): NSApi
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));

        $client = new Client([
            'base_uri' => 'https://gateway.apiportal.ns.nl/reisinformatie-api/api/v2/',
            'handler' => $stack,
            'http_errors' => false,
            'headers' => array_merge(['Ocp-Apim-Subscription-Key' => 'test-key'], $headers),
        ]);

        return new NSApi($client, 'test-key');
    }
}
