<?php

namespace Tests;

use Micronative\MockServer\Exceptions\ConfigException;
use Micronative\MockServer\Routing\Router;
use Micronative\MockServer\Server;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = $this->getRouter();
        parent::setUp();
    }

    /**
     * @dataProvider getValidData
     * @param array $data
     * @return void
     * @throws ConfigException
     */
    public function testRun(array $data): void
    {
        $request = $data['request'];
        $server = new Server($data['config'], $this->router);

        $this->router->expects(self::exactly(1))->method('process');
        $server->run($request);
    }

    public function getValidData(): array
    {
        return [
            [
                [
                    'config' => [],
                    'request' => $this->getRequest('/product/{id}', 'GET'),
                    'response' => [
                        'content' => '{}',
                        'code' => 404,
                    ]
                ],
                [
                    'config' => [],
                    'request' => $this->getRequest('/product/{id}', 'POST'),
                    'response' => [
                        'content' => '{}',
                        'code' => 200,
                    ]
                ]
            ]
        ];
    }

    /**
     * @param string $pathInfo
     * @param string $method
     * @return MockObject
     */
    private function getRequest(string $pathInfo, string $method): MockObject
    {
        $request = $this->createMock(Request::class);
        $request->method('getPathInfo')->willReturn($pathInfo);
        $request->method('getMethod')->willReturn($method);

        return $request;
    }

    /**
     * @return MockObject
     */
    private function getRouter(): MockObject
    {
        $router = $this->createMock(Router::class);
        foreach ($this->getValidData() as $dataSet) {
            foreach ($dataSet as $data) {
                $router->method('process')->with($data['request'])->willReturn($this->getResponse($data['response']['content'], $data['response']['code']));
            }
        }

        return $router;
    }

    /**
     * @param string $content
     * @param int $code
     * @return Response
     */
    private function getResponse(string $content, int $code): Response
    {
        return new Response($content, $code);
    }
}
