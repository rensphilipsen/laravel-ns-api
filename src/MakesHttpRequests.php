<?php

namespace RensPhilipsen\NSApi;

use Exception;
use Psr\Http\Message\ResponseInterface;
use RensPhilipsen\NSApi\Exceptions\TimeoutException;
use RensPhilipsen\NSApi\Exceptions\NotFoundException;
use RensPhilipsen\NSApi\Exceptions\ValidationException;
use RensPhilipsen\NSApi\Exceptions\FailedActionException;

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
    public function get($uri)
    {
        return $this->request('GET', $uri);
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
    public function post($uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
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
    public function put($uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
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
    public function delete($uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make request to NS servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    private function request($verb, $uri, array $payload = [])
    {
        $response = $this->guzzle->request($verb, $uri,
            empty($payload) ? [] : ['form_params' => $payload]
        );

        if ($response->getStatusCode() != 200) return $this->handleRequestError($response);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['payload'];
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
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        throw new Exception((string) $response->getBody());
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

        if ($output = $callback()) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep($sleep);

            goto beginning;
        }

        throw new TimeoutException($output);
    }
}