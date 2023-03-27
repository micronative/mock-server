<?php

namespace Tests\Routing;

use Micronative\MockServer\Builders\EndpointBuilder;
use Micronative\MockServer\Builders\RouterBuilder;
use Micronative\MockServer\Exceptions\RequestMethodException;
use Micronative\MockServer\Routing\Router;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $dir = dirname(__FILE__, 2) . '/assets/config/';
        $endpointBuilder = new EndpointBuilder([$dir . 'product-api.yml', $dir . 'user-api.yml']);
        $endpoints = $endpointBuilder->build();
        $routerBuilder = new RouterBuilder($endpoints);
        $this->router = $routerBuilder->build();
        parent::setUp();
    }

    /**
     * @dataProvider getValidData
     * @param array $data
     * @return void
     * @throws RequestMethodException
     */
    public function testProcess(array $data)
    {
        $response = $this->router->process($data['request']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @dataProvider getInvalidData
     * @param array $data
     * @return void
     * @throws RequestMethodException
     */
    public function testProcessThrowsException(array $data): void
    {
        $this->expectException($data['exception']);
        $this->router->process($data['request']);
    }

    public function getValidData(): array
    {
        return [
            [
                [
                    'request' => $this->getRequest('/product/{id}', 'GET'),
                ],
                [
                    'request' => $this->getRequest('/product/{id}', 'POST'),
                ]
            ]
        ];
    }

    public function getInvalidData(): array
    {
        return [
            [
                [
                    'request' => $this->getRequest('/user/product/{id}', 'GET'),
                    'exception' => ResourceNotFoundException::class
                ],
                [
                    'request' => $this->getRequest('/product/{id}', 'PATCH'),
                    'exception' => RequestMethodException::class
                ]
            ]
        ];
    }


    /**
     * @param string $pathInfo
     * @param string $method
     * @return MockObject|Request
     */
    private function getRequest(string $pathInfo, string $method)
    {
        $request = $this->createMock(Request::class);
        $request->method('getPathInfo')->willReturn($pathInfo);
        $request->method('getMethod')->willReturn($method);

        return $request;
    }
}