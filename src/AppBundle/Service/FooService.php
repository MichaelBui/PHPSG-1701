<?php

namespace AppBundle\Service;

use AppBundle\Entity\Entity;
use AppBundle\Event\FooCompletedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FooService
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function process(Entity $entity)
    {
        sleep(10);
        $this->eventDispatcher->dispatch(FooCompletedEvent::NAME, new FooCompletedEvent($entity));
    }
}