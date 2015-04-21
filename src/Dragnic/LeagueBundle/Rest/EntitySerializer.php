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
        $string = json_encode($entity->toArray());

        return $string;
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
        if (array_key_exists('data', $data)) {
            $object = $this->factoryEntities($data['data'], $accessor);
        } else {
            $object = null;
        }

        return $object;
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
        $properties = array(
            '__accessor' => $accessor,
        );

        foreach ($data as $propertyName => $value) {
            $properties[$propertyName] = $this->factoryEntities($value, $propertyName);
        }

        $entity = new Entity($properties);

        return $entity;
    }
}