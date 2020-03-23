<?php

namespace akiraz\LaravelTestPackage;

use akiraz\LaravelTestPackage\Exceptions\ApiException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TestResponseMiddleware
{
    public function __invoke(callable $handler): \Closure
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            /** @var PromiseInterface $promise */
            $promise = $handler($request, $options);

            return $promise->then(function (ResponseInterface $response) {

                if (!$this->isSuccessful($response)) {
                    $this->handleErrorResponse($response);
                }
                //@todo JsonException
                $body = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
                $this->validateJsonResponse($body);
                if ($body['success'] !== true) {
                    throw new ApiException($body['data']);
                }
                return $response->withBody($body);
            });
        };
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    protected function isSuccessful(ResponseInterface $response)
    {
        return $response->getStatusCode() === 200;
    }

    /**
     * @param ResponseInterface $response
     * @todo заполнить в соответствии с ТЗ
     */
    protected function handleErrorResponse(ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {

        }
    }

    /**
     * @param array $body
     * @throws \Exception
     */
    protected function validateJsonResponse(array $body)
    {
        if (!isset($body['status']) || !isset($body['data'])) {
            //@todo own JsonErrorException
            throw new \Exception('Неправильно сформирован ответ');
        }
    }
}
