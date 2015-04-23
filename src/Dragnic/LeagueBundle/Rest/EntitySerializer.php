<?php
namespace Dragnic\LeagueBundle\Rest;

use Dragnic\LeagueBundle\Entity\Entity;
use Dragnic\LeagueBundle\Entity\LazyEntity;
use Dragnic\LeagueBundle\Repository\Repository;

class EntitySerializer
{
    /** @var Repository */
    private $repository;

    public function __construct(array $lazyMap = null)
    {
        $this->lazyMap = null === $lazyMap ? array() : $lazyMap;
        $this->repository = null;
    }

    /**
     * @inheritDoc
     */
    public function serialize(Entity $entity)
    {
        return $this->serializeProperties($entity->toArray());
    }

    /**
     * @inheritDoc
     */
    public function deserialize($string, $accessor = null, Repository $repository = null)
    {
        if (null !== $repository && null === $this->repository) {
            $this->repository = $repository;
        }

        $data = json_decode($string, true);
        $data = array_key_exists('data', $data) ? $data['data'] : $data;
        $object = $this->factoryEntities($data, $accessor);

        return $object;
    }

    public function serializeProperties(array $properties)
    {
        $string = json_encode($properties);

        return $string;
    }

    public function deserializeProperties($string)
    {
        $data = json_decode($string, true);
        $properties = $this->parseProperties($data);

        return $properties;
    }

    protected function factoryEntities($data, $accessor = null)
    {
        if (is_array($data)) {
            $result = $this->factoryEntitiesFromArray($data, $accessor);
        } else if (is_scalar($data)) {
            $result = $data;
        } else {
            $result = null;
        }

        return $result;
    }

    protected function factoryEntitiesFromArray(array $data, $accessor = null) {
        if ($this->isCollection($data)) {
            $result = $this->createCollection($data, $accessor);
        } else {
            $result = $this->createEntity($data, $accessor);
        }

        return $result;
    }

    protected function isCollection(array $data)
    {
        $keys = array_keys($data);

        return count($keys) && is_numeric($keys[0]);
    }

    protected function createCollection(array $data, $accessor = null)
    {
        $collection = new \ArrayObject();

        foreach ($data as $value) {
            $value = $this->factoryEntities($value, $accessor);
            if ($value) {
                $collection->append($value);
            }
        }

        return $collection;
    }

    protected function createEntity(array $data, $accessor = null)
    {
        $properties = $this->parseProperties($data);
        $properties['__accessor'] = $accessor;

        $entity = new Entity($properties);

        return $entity;
    }

    protected function parseProperties(array $data)
    {
        $properties = array();

        foreach ($data as $propertyName => $value) {
            $properties[$propertyName] = $this->factoryEntities($value, $propertyName);
        }

        return $properties;
    }
}