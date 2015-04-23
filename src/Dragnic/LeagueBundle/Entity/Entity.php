<?php
namespace Dragnic\LeagueBundle\Entity;

use Dragnic\LeagueBundle\Exception;
use Doctrine\ORM\Mapping as ORM;
use Dragnic\LeagueBundle\Rest\EntitySerializer;

/**
 * @ORM\Entity
 */
class Entity extends PropertyIteratorAggregate
{
    const NAME = 'DragnicLeagueBundle:Entity';
    /**
     * @var  array
     * @ORM\Column(type="text")
     */
    protected $properties;
    /**
     * @var string
     * @ORM\Column(type="string")
     * @ORM\Id
     */
    protected $id;

    protected $taxonomies;

    /**
     * @return mixed
     */
    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    /**
     * @param mixed $taxonomies
     */
    public function setTaxonomies($taxonomies)
    {
        $this->taxonomies = $taxonomies;
    }

    public function __construct($properties = array())
    {
        $this->properties = $properties;
        $this->id = static::generateId($properties);
    }

    public static function generateId(array $properties)
    {
        $id = null;

        if (array_key_exists('__accessor', $properties) && array_key_exists('id', $properties)) {
            $id = $properties['__accessor'] . '-' . $properties['id'];
        }

        return $id;
    }

    public function toArray()
    {
        return $this->flattenArray($this->properties);
    }

    protected function flattenArray(array $toFlatten)
    {
        $properties = array();

        foreach ($toFlatten as $propertyName => $propertyValue) {
            if (is_scalar($propertyValue)) {
                $value = $propertyValue;
            } else if (is_object($propertyValue) && $propertyValue instanceof Entity) {
                $value = $propertyValue->toArray();
            } else if (is_object($propertyValue) && $propertyValue instanceof \ArrayObject) {
                $value = $this->flattenArray($propertyValue->getArrayCopy());
            } else {
                throw new \Exception('Failed to convert Entity to array');
            }

            $properties[$propertyName] = $value;
        }

        return $properties;
    }

    public function getInternalId()
    {
        return $this->id;
    }

    public function serializeProperties(EntitySerializer $serializer)
    {
        $this->properties = $serializer->serializeProperties($this->toArray());
    }

    public function deserializeProperties(EntitySerializer $serializer)
    {
        $this->properties = $serializer->deserializeProperties($this->properties);
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
        } elseif ($this->hasProperty($method)) {
            $property = $method;
            $value = $this->getProperty($property);
        } else {
            throw new Exception\UnknownMethodException('The method "' . $method . '" is not supported.');
        }

        if (!$this->hasProperty($property)) {
            throw new Exception\UnknownPropertyException('The property "' . $property . '" is not supported.');
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