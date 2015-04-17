<?php
namespace Dragnic\LeagueBundle\Rest;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class EntitySerializer implements SerializerInterface
{
    private $serializer;
    private $typeMap;

    public function __construct(SerializerInterface $serializer, array $typeMap, $defaultFormat)
    {
        $this->serializer = $serializer;
        $this->typeMap = $typeMap;
        $this->defaultFormat = $defaultFormat;
    }

    /**
     * @inheritDoc
     */
    public function serialize($data, $format = null, SerializationContext $context = null)
    {
        return $this->serializer->serialize($data, $this->getFormat($format), $context);
    }

    /**
     * @inheritDoc
     */
    public function deserialize($data, $type = null, $format = null, DeserializationContext $context = null)
    {
        $type = $this->getType($type);
        $format = $this->getFormat($format);

        return $this->serializer->deserialize($data, $type, $format, $context);
    }

    protected function getType($type = null)
    {
        if (null !== $type && array_key_exists($type, $this->typeMap)) {
            $type = $this->typeMap[$type];
        }

        return $type;
    }

    protected function getFormat($format = null)
    {
        $format = null === $format ? $this->defaultFormat : $format;

        return $format;
    }
}