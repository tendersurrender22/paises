<?php

namespace InicioBundle\Form\Listener;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Doctrine\ORM\QueryBuilder;

class AddStateFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
          //FormEvents::PRE_SET_DATA => 'preSetData',
          FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    /*public function preSetData(FormEvent $event)
    {
        $user = $event->getData();

        $country = ($user and $user->getCountry()) ? $user->getCountry() : null;

        $this->addField($event->getForm(),  $country);
    }*/

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
//var_dump($data);exit();
        $this->addField($event->getForm(), $data['country']);
    }

    protected function addField(Form $form, $country)
    {
        $form->add('state', EntityType::class, array(
            'class' => 'InicioBundle:State',
            'query_builder' => function(EntityRepository $er) use ($country){
                return $er->createQueryBuilder('state')
                    ->where('state.country = :country')
                    ->setParameter('country', $country);
            }
        ));
    }
}
