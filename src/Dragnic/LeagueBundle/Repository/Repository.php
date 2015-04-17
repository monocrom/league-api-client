<?php
namespace Dragnic\LeagueBundle\Repository;

use Doctrine\ORM\EntityManager;
use Dragnic\LeagueBundle\Rest\Client;

class Repository
{
    private $entityManager;
    private $client;

    public function __construct(EntityManager $entityManager, Client $client)
    {
        $this->entityManager = $entityManager;
        $this->client = $client;
    }

    /**
     * @param string $type
     * @return array
     */
    public function findAll($type)
    {
        $entities = $this->client->get($type);

        foreach ($entities as $entity) {

        }

        return $entities;
    }

    /**
     * @param string $type
     * @param string $id
     * @return object
     */
    public function find($type, $id)
    {
        $entity = $this->client->get($type, array($id));

        return $entity;
    }

    /**
     * @param $type
     * @param array $criteria
     * @return array
     */
    public function findBy($type, array $criteria)
    {
        $entites = $this->client->get($type, $criteria);

        return $entites;
    }

    /**
     * @param $type
     * @param array $criteria
     * @return object
     */
    public function findOneBy($type, array $criteria)
    {
        $entity = $this->client->get($type, $criteria);

        return $entity;
    }
}