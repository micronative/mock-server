<?php

namespace Tests\Routing;

use Micronative\MockServer\Config\Endpoint;
use Micronative\MockServer\Config\Response;
use Micronative\MockServer\Exceptions\RequestMethodException;
use Micronative\MockServer\Routing\EndpointMatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class EndpointMatcherTest extends TestCase
{
    /**
     * @dataProvider getValidData
     * @param array $data
     * @return void
     * @throws RequestMethodException
     */
    public function testMatch(array $data): void
    {
        $matcher = new EndpointMatcher($data['data']);
        $request = $data['request'];
        $endPoint = $matcher->match($request);

        $this->assertInstanceOf(Endpoint::class, $endPoint);
    }

    /**
     * @dataProvider getInvalidData
     * @param array $data
     * @return void
     */
    public function testMatchThrowsException(array $data): void
    {
        $matcher = new EndpointMatcher($data['data']);
        $request = $data['request'];

        $this->expectException(RequestMethodException::class);
        $matcher->match($request);
    }

    public function getValidData(): array
    {
        return [
            [
                [
                    'request' => $this->getRequest('GET'),
                    'data' => ['_endpoint' => new Endpoint('/product/{id}', ['GET', 'POST'], new Response(200, 'json', '{ "id":"5e8e44a0-2552-4acc-9964-f5ca07948486", "payload":{ "error":"Resource Not Found" }, "created_at":"2022-09-05 03:37:54" }'))]
                ],
                [
                    'request' => $this->getRequest('POST'),
                    'data' => ['_endpoint' => new Endpoint('/product/{id}', ['GET', 'POST'], new Response(200, 'json', '{ "id":"5e8e44a0-2552-4acc-9964-f5ca07948486", "payload":{ "error":"Resource Not Found" }, "created_at":"2022-09-05 03:37:54" }'))]
                ]
            ]
        ];
    }

    public function getInvalidData(): array
    {
        return [
            [
                [
                    'request' => $this->getRequest('GET'),
                    'data' => ['_endpoint' => new Endpoint('/product/{id}', ['POST'], new Response(200, 'json', '{ "id":"5e8e44a0-2552-4acc-9964-f5ca07948486", "payload":{ "error":"Resource Not Found" }, "created_at":"2022-09-05 03:37:54" }'))]
                ],
                [
                    'request' => $this->getRequest('POST'),
                    'data' => ['_endpoint' => new Endpoint('/product/{id}', ['GET'], new Response(200, 'json', '{ "id":"5e8e44a0-2552-4acc-9964-f5ca07948486", "payload":{ "error":"Resource Not Found" }, "created_at":"2022-09-05 03:37:54" }'))]
                ]
            ]
        ];
    }


    /**
     * @param string $method
     * @return Request
     */
    private function getRequest(string $method): Request
    {
        $request = new Request();
        $request->setMethod($method);
        return $request;
    }
}