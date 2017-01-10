<?php

namespace AppBundle\Form;

use AppBundle\Entity\Entity;
use AppBundle\EventSubscriber\FooSubscriber;
use AppBundle\EventSubscriber\LogSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormType extends AbstractType
{
    private $fooSubscriber;
    private $logSubscriber;

    public function __construct(FooSubscriber $fooSubscriber, LogSubscriber $logSubscriber)
    {
        $this->fooSubscriber = $fooSubscriber;
        $this->logSubscriber = $logSubscriber;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Name: '])
            ->add('save', SubmitType::class, ['label' => 'Submit']);

        $builder->addEventSubscriber($this->logSubscriber);
        $builder->addEventSubscriber($this->fooSubscriber);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entity::class
        ]);
    }
}