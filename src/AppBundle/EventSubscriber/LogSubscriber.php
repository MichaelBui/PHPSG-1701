<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Entity;
use AppBundle\Event\FooCompletedEvent;
use AppBundle\Service\LogService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class LogSubscriber implements EventSubscriberInterface
{
    private $log;

    public function __construct(LogService $log)
    {
        $this->log = $log;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'onFormSubmitted',
            FooCompletedEvent::NAME => 'onFooCompleted',
        ];
    }

    public function onFormSubmitted(FormEvent $event)
    {
        /** @var Entity $entity */
        $entity = $event->getData();
        $this->log->logSubmitted($entity->getName());
    }

    public function onFooCompleted(FooCompletedEvent $event)
    {
        $entity = $event->getEntity();
        $this->log->logProcessed($entity->getName());
    }
}