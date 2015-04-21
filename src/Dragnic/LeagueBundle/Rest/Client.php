<?php
namespace Dragnic\LeagueBundle\Rest;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\Routing\RouterInterface;

class Client
{
    private $router;
    private $client;
    private $baseUrl;
    private $routePrefix;
    private $apiKey;
    private $routeExtraParameters;

    public function __construct(RouterInterface $router, $baseUrl, $routePrefix, $apiKey, array $routeExtraParameters)
    {
        $this->router = $router;
        $this->client = null;
        $this->baseUrl = $baseUrl;
        $this->routePrefix = $routePrefix;
        $this->apiKey = $apiKey;
        $this->routeExtraParameters = $routeExtraParameters;
    }

    public static function isCollectionRequest($routeName)
    {
        return preg_match('/s$/', $routeName);
    }

    public function get($routeName, array $parameters = array())
    {
        $url = $this->generateRoute($routeName, $parameters);
        var_dump($url . '<br>');
        $request = $this->createClient()->get($url);
        try {
            $response = $request->send();
            $result = $response->getBody(true);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            if (404 === $response->getStatusCode()) {
                $result = $this->isCollectionRequest($routeName) ? '[]' : '';
            } else {
                throw $e;
            }
        }

        return $result;
    }

    protected function generateRoute($routeName, array $parameters = array())
    {
        $parameters['api_key'] = $this->apiKey;

        if (array_key_exists($routeName, $this->routeExtraParameters)) {
            $parameters = array_merge($parameters, $this->routeExtraParameters[$routeName]);
        }

        $url = $this->router->generate($this->routePrefix . $routeName, $parameters);

        return $url;
    }

    protected function createClient()
    {
        if (!$this->client) {
            $this->client = new GuzzleClient($this->baseUrl);
        }

        return $this->client;
    }
}