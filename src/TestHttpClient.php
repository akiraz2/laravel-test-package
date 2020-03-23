<?php

namespace akiraz\LaravelTestPackage;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class TestHttpClient implements ClientInterface
{
    protected $config = [];
    protected $client;

    /**
     * TestHttpClient constructor.
     */
    public function __construct()
    {
        $this->config = config('test');
        $stack = HandlerStack::create();
        $stack->push(new TestResponseMiddleware());
        $this->config['handler'] = $stack;
        $this->client = new Client($this->config);
    }

    /**
     * @param string $uri
     * @return ResponseInterface
     */
    public function fetch(string $uri)
    {
        return $this->client->request('GET', $uri);
    }

    /**
     * example $request = new Request('GET', 'http://httpbin.org/get', $headers, $body);
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->client->send($request);
    }
}
