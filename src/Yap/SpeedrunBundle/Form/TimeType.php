<?php

namespace Yap\SpeedrunBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('time', 'time')
            ->add('note', 'textarea')
            /*->add('level', 'entity', array('class' => 'YapSpeedrunBundle:Level', 'property' => 'name'))
            /*->add('linker', 'entity', array('class' => 'YapSpeedrunBundle:Linker'))*/
            /*->add('user', 'entity', array('class' => 'YapUserBundle:User'))*/
            ->add('video', 'text')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yap\SpeedrunBundle\Entity\Time'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yap_speedrunbundle_time';
    }
}
