<?php

namespace InicioBundle\Form\Listener;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AddStateFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
          FormEvents::PRE_SET_DATA => 'preSetData', //nuevo evento escuchado
          FormEvents::PRE_SUBMIT => 'preSubmit',
        );
    }

    /**
     * Este evento se ejecuta al momento de crear el formulario
     * o al llamar al método $form->setData($user),
     * y nos sirve para obtener datos inicales del objeto asociado al form.
     * Ya que por ejemplo si el objeto viene de la base de datos y contiene
     * ya un pais establecido, lo ideal es que el campo state se carge inicalmente con
     * los estados de dicho pais.
     */
    public function preSetData(FormEvent $event)
    {
        $user = $event->getData(); //data es un objeto AppBundle\Entity\User

        // Pasamos siempre el country así sea null
        // para que cuando sea un usuario nuevo, el listado de estados esté
        // vacio inicialmente, y solo se llene de items, cuando se ejecute el
        // ajax que obtiene los estados del pais seleccionado por el usuario.

        $country = ($user and $user->getCountry()) ? $user->getCountry() : null; // Importante los parentesis al usar "and".

        // Es importante siempre verificar que el valor devuelto por $event->getData()
        // (que en este caso es $user) no sea null, porque no es obligatorio que al crear
        // el formulario, se le pase una instancia de User,
        // y si no se le pasa, User será nulo.

        $this->addField($event->getForm(),  $country);
    }

    /**
     * Cuando el usuario llene los datos del formulario y haga el envío del mismo,
     * este método será ejecutado.
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        //data es un arreglo con los valores establecidos por el usuario en el form.
        //como $data contiene el pais seleccionado por el usuario al enviar el formulario,
        // usamos el valor de la posicion $data['country'] para filtrar el sql de los estados
        $this->addField($event->getForm(), $data['country']);
    }

    protected function addField(Form $form, $country)
    {
        // actualizamos el campo state, pasandole el country a la opción
        // query_builder, para que el dql tome en cuenta el pais
        // y filtre la consulta por su valor.
        $form->add('state', EntityType::class, array(
            'class' => 'InicioBundle\Entity\State',
            'query_builder' => function(EntityRepository $er) use ($country){
                return $er->createQueryBuilder('state')
                    ->where('state.country = :country')
                    ->setParameter('country', $country);
            }
        ));
    }
}
