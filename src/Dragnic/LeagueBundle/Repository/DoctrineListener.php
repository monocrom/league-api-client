<?php
namespace Dragnic\LeagueBundle\Repository;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Dragnic\LeagueBundle\Entity\Entity;
use Dragnic\LeagueBundle\Rest\EntitySerializer;

class DoctrineListener
{
    private $serializer;

    public function __construct(EntitySerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        /** @var Entity $entity */
        $entity = $eventArgs->getEntity();
        $entity->deserializeProperties($this->serializer);
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        /** @var Entity $entity */
        $entity = $eventArgs->getEntity();
        $entity->serializeProperties($this->serializer);
    }
}