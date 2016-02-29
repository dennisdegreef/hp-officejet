<?php

namespace Link0\Hp\OfficeJet\HttpClient;

use GuzzleHttp\Client;
use Link0\Hp\OfficeJet\HttpClient;

final class GuzzleHttpClient implements HttpClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * GuzzleHttpClient constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param null   $body
     *
     * @return string
     * @throws \Exception
     */
    public function request($method, $uri, $body = null)
    {
        $request = new \GuzzleHttp\Psr7\Request($method, $uri, [], $body);
        $response = $this->client->send($request);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getStatusCode() . ': ' . $response->getReasonPhrase());
        }

        return $response->getBody()->getContents();
    }
}