<?php

namespace Yap\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',          'text')
            ->add('user', 'entity', array('class' => 'YapUserBundle:User'))
            ->add('content',        'textarea')
            ->add('date',           'date')
            ->add('publication',    'checkbox', array('required' => false))
            ->add('image',          new ImageType(), array('required' => false))
            ->add('categories',   'entity', array('class' => 'YapBlogBundle:Category',
                                                    'property' => 'name',
                                                    'multiple' => true,
                                                    'expanded' => false
                                                    ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yap\BlogBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yap_blogbundle_articletype';
    }
}
