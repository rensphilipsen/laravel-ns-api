<?php

namespace RensPhilipsen\NSApi;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use RensPhilipsen\NSApi\Exceptions\TimeoutException;
use RensPhilipsen\NSApi\Exceptions\NotFoundException;
use RensPhilipsen\NSApi\Exceptions\ValidationException;
use RensPhilipsen\NSApi\Exceptions\FailedActionException;

/**
 * @property \GuzzleHttp\ClientInterface $guzzle
 */
trait MakesHttpRequests
{
    /**
     * Make a GET request to NS servers and return the response.
     *
     * @param string $uri
     * @return mixed
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function get(string $uri, array $query = [], array $headers = [])
    {
        return $this->request('GET', $uri, [
            'query' => $query,
            'headers' => $headers,
        ]);
    }

    /**
     * Make a POST request to NS servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function post(string $uri, array $payload = [], array $options = [])
    {
        if ($payload !== [] && !isset($options['form_params'], $options['json'], $options['multipart'], $options['body'])) {
            $options['form_params'] = $payload;
        }

        return $this->request('POST', $uri, $options);
    }

    /**
     * Make a PUT request to NS servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function put(string $uri, array $payload = [], array $options = [])
    {
        if ($payload !== [] && !isset($options['form_params'], $options['json'], $options['multipart'], $options['body'])) {
            $options['form_params'] = $payload;
        }

        return $this->request('PUT', $uri, $options);
    }

    /**
     * Make a DELETE request to NS servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function delete(string $uri, array $payload = [], array $options = [])
    {
        if ($payload !== [] && !isset($options['form_params'], $options['json'], $options['multipart'], $options['body'])) {
            $options['form_params'] = $payload;
        }

        return $this->request('DELETE', $uri, $options);
    }

    /**
     * Make a POST request with a JSON payload.
     */
    public function postJson(string $uri, array $payload = [], array $options = [])
    {
        $options['json'] = $payload;

        return $this->request('POST', $uri, $options);
    }

    /**
     * Make a multipart POST request.
     */
    public function postMultipart(string $uri, array $parts = [], array $options = [])
    {
        $options['multipart'] = $parts;

        return $this->request('POST', $uri, $options);
    }

    /**
     * Make request to NS servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $options
     * @return mixed
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    private function request(string $verb, string $uri, array $options = [])
    {
        $response = null;

        try {
            $response = $this->guzzle->request($verb, $uri, $options);
        } catch (ConnectException $exception) {
            throw new TimeoutException([
                'message' => $exception->getMessage(),
            ]);
        } catch (BadResponseException $exception) {
            $this->handleRequestError($exception->getResponse());
        } catch (RequestException $exception) {
            throw new FailedActionException($exception->getMessage());
        } catch (GuzzleException $exception) {
            throw new FailedActionException($exception->getMessage());
        }

        if ($response === null) {
            throw new FailedActionException('The request did not return a response.');
        }

        if ($response->getStatusCode() >= 400) {
            $this->handleRequestError($response);
        }

        $data = $this->decodeResponseBody($response);

        if (is_array($data) && array_key_exists('payload', $data)) {
            return $data['payload'];
        }

        return $data;
    }

    /**
     * Decode the HTTP response body.
     *
     * @return mixed
     */
    private function decodeResponseBody(ResponseInterface $response)
    {
        $body = (string) $response->getBody();

        if ($body === '') {
            return [];
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $body;
        }

        return $decoded;
    }

    /**
     * @param ResponseInterface $response
     * @return void
     * @throws ValidationException
     * @throws NotFoundException
     * @throws FailedActionException
     * @throws Exception
     */
    private function handleRequestError(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $body = $this->decodeResponseBody($response);

        if ($statusCode === 422) {
            throw new ValidationException(is_array($body) ? ($body['errors'] ?? $body) : []);
        }

        if ($statusCode === 404) {
            $message = is_array($body) ? ($body['message'] ?? null) : null;

            throw new NotFoundException($message);
        }

        if ($statusCode === 400 || $statusCode === 429) {
            $message = is_array($body) ? ($body['message'] ?? json_encode($body)) : (string) $body;

            throw new FailedActionException($message ?: 'The request failed.');
        }

        $message = is_array($body) ? ($body['message'] ?? json_encode($body)) : (string) $body;

        throw new Exception($message ?: sprintf('Request failed with status code %d.', $statusCode));
    }

    /**
     * Retry the callback or fail after x seconds.
     *
     * @param integer $timeout
     * @param callable $callback
     * @param integer $sleep
     * @return mixed
     * @throws TimeoutException
     */
    public function retry($timeout, $callback, $sleep = 5)
    {
        $start = time();

        beginning:

        $output = $callback();

        if ($output !== false && $output !== null) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep($sleep);

            goto beginning;
        }

        throw new TimeoutException($output);
    }
}
