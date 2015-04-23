<?php
namespace Dragnic\LeagueBundle\Twig;

use Dragnic\LeagueBundle\Entity\Entity;
use Dragnic\LeagueBundle\Rest\Client;
use Dragnic\LeagueBundle\Rest\EntitySerializer;

class ImageExtension extends  \Twig_Extension
{
    private $client;
    private $serializer;

    public function __construct(Client $client, EntitySerializer $serializer)
    {
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('league_image', array($this, 'getImage'))
        );
    }

    /**
     * @param Entity $champion
     * @return string
     */
    public function getImage(Entity $champion)
    {
        $imageFileName = $champion->getImage()->getFull();
        $realm = $this->getRealm();
        $cdn = $realm->getCdn();
        $version = $realm->getV();
        $path = "$cdn/$version/img/champion/$imageFileName";

        return $path;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'league_image_extension';
    }

    protected function getRealm()
    {
        $response = $this->client->get('realm', array(), true);
        $realm = $this->serializer->deserialize($response);

        return $realm;
    }
}