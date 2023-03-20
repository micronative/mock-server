<?php

namespace Micronative\MockServer\Builders;

use Micronative\MockServer\Config\Response;

class ResponseBuilder implements BuilderInterface
{
    private array $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function build(): Response
    {
        return new Response($this->response['code'], $this->response['format'], $this->response['content']);
    }
}
