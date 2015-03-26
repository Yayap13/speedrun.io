<?php

namespace Yap\SpeedrunBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',       'text', array('label' => 'Name'))
            //->add('visible',    'checkbox', array('required' => false))
            ->add('levels',     'collection', array('type' => new LevelType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('difficulties','collection', array('type' => new DifficultyType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false))
            ->add('imageFile',      'file', array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yap\SpeedrunBundle\Entity\Game'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yap_speedrunbundle_game';
    }
}
