<?php

namespace Yap\SpeedrunBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class LinkerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $factory = $builder->getFormFactory();

        $builder
            ->add('name', 'text', array('label' => 'Name'))
            ->add('game', 'entity', array('class' => 'YapSpeedrunBundle:Game', 'property' => 'name', 'label' => 'Game'))
            ->add('difficulty', 'entity', array('class' => 'YapSpeedrunBundle:Difficulty', 'property' => 'name', 'label' => 'Difficulty'))
            ->add('category', 'entity', array('class' => 'YapSpeedrunBundle:Category', 'property' => 'name', 'label' => 'Category'))
            ->add('scripted', 'checkbox', array('required' => false, 'label' => 'Scripted'))
            ->add('segmented', 'checkbox', array('required' => false, 'label' => 'Segmented'))
            ->add('tas', 'checkbox', array('required' => false, 'label' => 'Tool Assisted'))
            ->add('version', 'text', array('label' => 'Game version', 'required' => false))
        ;

        $refreshDifficulty = function ($form, $game) use ($factory) {
            $form->add('difficulty', 'entity', array(
                    'class' => 'Yap\SpeedrunBundle\Entity\Difficulty',
                    'property' => 'name',
                    'label' => 'Difficulty',
                    'query_builder' => function (EntityRepository $repository) use ($game) {
                        $qb = $repository->createQueryBuilder('difficulty')
                                            ->innerJoin('difficulty.game', 'game');

                        if ($game instanceof Game) {
                            $qb = $qb->where('difficulty.game = :game')
                                        ->setParameter('game', $game);
                        } else if (is_numeric($game)) {
                            $qb = $qb->where('game.id = :game_id')
                                        ->setParameter('game_id', $game);
                        } else {
                            $qb = $qb->where('game.id = 1');
                        }

                        return $qb;
                    }
                ));
        };

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($refreshDifficulty) {
            $form = $event->getForm();
            $data = $event->getData();

            if($data == null) {
                $refreshDifficulty($form, null);
            }

            if($data instanceof Game) {
                $refreshDifficulty($form, $data->getDifficulty()->getGame());
            }
        });

        $builder->addEventListener(FormEvents::PRE_BIND, function (FormEvent $event) use ($refreshDifficulty) {
            $form = $event->getForm();
            $data = $event->getData();

            if(array_key_exists('game', $data)) {
                $refreshDifficulty($form, $data['game']);
            }
        });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Yap\SpeedrunBundle\Entity\Linker'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'yap_speedrunbundle_linker';
    }
}
