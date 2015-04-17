<?php
namespace Dragnic\LeagueBundle\Entity;

use Dragnic\LeagueBundle\Exception;

class Entity
{
    /** @var  array */
    protected $properties;

    public function __construct($properties = array())
    {
        $this->properties = $properties;
    }

    public function setChampions()
    {
        var_dump('test');die;
    }

    public function toArray()
    {
        return $this->properties;
    }

    public function __call($method, $arguments)
    {
        $operator = substr($method, 0, 3);
        $isOperator = substr($method, 0, 2);
        $property = lcfirst(substr($method, 3));

        if ('get' === $operator) {
            $value = $this->getProperty($property);
        } elseif ('set' === $operator) {
            if (0 === count($arguments)) {
                throw new Exception\InsufficientArgumentsException('"' . $method . '" expects at least one argument.');
            }
            $value = $this->setProperty($property, $arguments[0]);
        } elseif ('is' === $isOperator) {
            $property = lcfirst(substr($method, 2));
            $value = boolval($this->getProperty($property));
        } else {
            throw new Exception\UnknownMethodException('The method "' . $method . '" is not supported.');
        }

        if (!$this->hasProperty($property)) {
            throw new Exception\UnknownMethodException('The property "' . $property . '" is not supported.');
        }

        return $value;
    }

    protected function setProperty($property, $value)
    {
        if ($this->hasProperty($property)) {
            $this->properties[$property] = $value;
        }

        return $this;
    }

    protected function getProperty($property)
    {
        if ($this->hasProperty($property)) {
            $value = $this->properties[$property];
        } else {
            $value = null;
        }

        return $value;
    }

    protected function hasProperty($property)
    {
        return array_key_exists($property, $this->properties);
    }
}