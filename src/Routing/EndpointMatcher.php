<?php

namespace Micronative\MockServer\Routing;

use Micronative\MockServer\Config\Endpoint;
use Micronative\MockServer\Exceptions\RequestMethodException;
use Symfony\Component\HttpFoundation\Request;

class EndpointMatcher
{
    private array $data;
    private Endpoint $endpoint;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->endpoint = $data['_endpoint'];
    }

    /**
     * @param Request $request
     * @return Endpoint
     * @throws RequestMethodException
     */
    public function match(Request $request): Endpoint
    {
        if (in_array($request->getMethod(), $this->endpoint->getMethods())){
            return $this->endpoint;
        }

        throw new RequestMethodException('Method not allowed: '. $request->getMethod(), 404);
    }

}
