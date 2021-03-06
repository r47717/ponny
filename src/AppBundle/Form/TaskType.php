<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task', 'text', [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('description', 'textarea', [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('status', 'text', [
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('due', 'genemu_jquerydate', [
                'widget' => 'single_text',
                'label' => 'label',
                'attr' => ['class' => 'datepicker form-control'],
                'format' => 'dd/MM/yyyy',
                'configs' => [          
                    'changeMonth' => true,
                    'changeYear' => true,
                    'maxDate' => 0,                        
                ]
            ])
            ->add('categories', 'entity', [
                'class' => 'AppBundle:Category',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('priority', 'choice', [
                'choices' => [1 => 1, 2, 3, 4, 5],
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'width: 60px',
                ],
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_task';
    }
}
