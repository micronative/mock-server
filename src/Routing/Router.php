<?php

namespace Micronative\MockServer\Routing;

use Micronative\MockServer\Exceptions\RequestMethodException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    private RouteCollection $routes;
    private UrlMatcher $urlMatcher;
    private RequestContext $requestContext;

    /**
     * @param RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
        $this->requestContext = new RequestContext();
        $this->urlMatcher = new UrlMatcher($this->routes, $this->requestContext);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws RequestMethodException | ResourceNotFoundException | MethodNotAllowedException | NoConfigurationException
     */
    public function process(Request $request): Response
    {
        $data = $this->urlMatcher->match($request->getPathInfo());
        $endpointMatcher = new EndpointMatcher($data);
        $endpoint = $endpointMatcher->match($request);
        $headers = [];
        if ($endpoint->getResponse()->getFormat() === 'json') {
            $headers = ['Content-Type: application/json; charset=utf-8'];
        }

        return new Response($endpoint->getResponse()->getContent(), $endpoint->getResponse()->getCode(), $headers);
    }

    /**
     * @return RouteCollection
     */
    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }

    /**
     * @param RouteCollection $routes
     * @return Router
     */
    public function setRoutes(RouteCollection $routes): Router
    {
        $this->routes = $routes;
        return $this;
    }
}
