<?php
namespace Dragnic\LeagueBundle\Entity;

use Dragnic\LeagueBundle\Exception;
use Dragnic\LeagueBundle\Repository\Repository;

class LazyEntity extends Entity
{
    /** @var  Repository */
    protected $repository;
    /** @var  string */
    protected $type;
    /** @var  boolean */
    protected $hasLazyLoaded;

    public function __construct($properties = array())
    {
        parent::__construct($properties);
        $this->hasLazyLoaded = false;
        $this->repository = null;
    }

    public function __call($method, $arguments)
    {
        try {
            $result = parent::__call($method, $arguments);
        } catch (Exception\UnknownCallException $e) {
            if ($this->lazyLoad()) {
                $result = parent::__call($method, $arguments);
            } else {
                throw $e;
            }
        }

        return $result;
    }

    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    protected function lazyLoad()
    {
        $success = false;

        if (null !== $this->repository) {
            /** @var Entity $entity */
            $entity = $this->repository->find($this->type, $this->getId());
            if (null !== $entity) {
                $success = true;
                var_dump('LazyEntity', $entity->properties);die;
                $this->properties &= $entity->properties;
            }
        }

        return $success;
    }
}