<?php
namespace Dragnic\LeagueBundle\Repository;

use Doctrine\ORM\EntityManager;
use Dragnic\LeagueBundle\Rest\Client;
use Dragnic\LeagueBundle\Rest\EntitySerializer;

class Repository
{
    private $entityManager;
    private $client;
    private $serializer;

    public function __construct(EntityManager $entityManager, Client $client, EntitySerializer $serializer)
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
        $this->serializer = $serializer;
    }

    /**
     * @param string $type
     * @return array
     */
    public function findAll($type)
    {
        $response = $this->client->get($type);
        $entities = $this->serializer->deserialize($response, $type, $this);

        return $entities;
    }

    /**
     * @param string $type
     * @param string $id
     * @return object
     */
    public function find($type, $id)
    {
        $response = $this->client->get($type, array('id' => $id));
        $entity = $this->serializer->deserialize($response, $type, $this);

        return $entity;
    }

    /**
     * @param $type
     * @param array $criteria
     * @return array
     */
    public function findBy($type, array $criteria)
    {
        $response = $this->client->get($type, $criteria);
        $entities = $this->serializer->deserialize($response, $type, $this);

        return $entities;
    }

    /**
     * @param $type
     * @param array $criteria
     * @return object
     */
    public function findOneBy($type, array $criteria)
    {
        $response = $this->client->get($type, $criteria);
        $entity = $this->serializer->deserialize($response, $type, $this);

        return $entity;
    }
}