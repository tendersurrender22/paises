<?php

namespace InicioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use InicioBundle\Form\Listener\AddStateFieldSubscriber;
//use InicioBundle\Form\State;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                //->add('username')
                //->add('password')
                //->add('email')
                //->add('status')
                ->add('country', EntityType::class, array(
                    'class' => 'InicioBundle:Country',
                    'choice_label' => 'name',
                    ))
                /*->add('state', EntityType::class, array(
                    'class' => 'InicioBundle:State',
                    'choice_label' => 'name',
                  ))*/
                ->add('save', SubmitType::class, array('label' => 'Enviar'))
                ;

        $builder->addEventSubscriber(new AddStateFieldSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InicioBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'iniciobundle_user';
    }


}
