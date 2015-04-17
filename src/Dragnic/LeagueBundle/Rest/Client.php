<?php
namespace Dragnic\LeagueBundle\Rest;

use Dragnic\LeagueBundle\Exception\UnsupportedMethodException;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\Routing\RouterInterface;

class Client
{
    private $router;
    private $serializer;
    private $client;
    private $baseUrl;
    private $routePrefix;
    private $apiKey;

    public function __construct(RouterInterface $router, EntitySerializer $serializer, $baseUrl, $routePrefix, $apiKey)
    {
        $this->router = $router;
        $this->serializer = $serializer;
        $this->client = null;
        $this->baseUrl = $baseUrl;
        $this->routePrefix = $routePrefix;
        $this->apiKey = $apiKey;
    }

    public function __call($method, $parameters)
    {
        $operator = substr($method, 0, 3);
        $routeName = lcfirst(substr($method, 3));

        if ('get' === $operator) {
            $value = $this->get($routeName, $parameters);
        } else {
            throw new UnsupportedMethodException('The method "' . $operator . '" is not supported.');
        }

        return $value;
    }

    public static function isCollectionRequest($routeName)
    {
        return preg_match('/s$/', $routeName);
    }

    public function get($routeName, array $parameters = array())
    {

        $request = $this->createClient()->get($this->generateRoute($routeName, $parameters));
        try {
            $response = $request->send();
            $json = $response->getBody(true);
            $value = $this->serializer->deserialize($json, $routeName);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            if (404 === $response->getStatusCode()) {
                var_dump($response->getBody(true));
                $value = $this->isCollectionRequest($routeName) ? $this->createCollection() : null;
            } else {
                throw $e;
            }
        }

        return $value;
    }

    protected function generateRoute($routeName, array $parameters = array())
    {
        $parameters['api_key'] = $this->apiKey;

        $url = $this->router->generate($this->routePrefix . $routeName, $parameters);

        return $url;
    }

    protected function createCollection()
    {
        return new \ArrayObject();
    }

    protected function createClient()
    {
        if (!$this->client) {
            $this->client = new GuzzleClient($this->baseUrl);
        }

        return $this->client;
    }
}