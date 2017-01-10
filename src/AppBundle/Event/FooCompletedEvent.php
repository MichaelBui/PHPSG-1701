<?php

namespace AppBundle\Event;

use AppBundle\Entity\Entity;
use Symfony\Component\EventDispatcher\Event;

class FooCompletedEvent extends Event
{
    const NAME = 'foo.completed';

    /**
     * @var Entity
     */
    private $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}