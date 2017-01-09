<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Entity;
use AppBundle\Event\FooCompletedEvent;
use AppBundle\Service\FooService;
use AppBundle\Service\LogService;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FooSubscriber implements EventSubscriberInterface
{
    private $foo;

    public function __construct(FooService $foo)
    {
        $this->foo = $foo;
    }

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'onFormSubmitted',
        ];
    }

    public function onFormSubmitted(FormEvent $event)
    {
        $this->foo->process($event->getData());
    }
}