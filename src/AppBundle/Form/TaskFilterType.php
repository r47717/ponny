<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uncompletedOnly', 'checkbox', [
                'label' => 'uncompleted only', 
                'attr' => [
                  'required' => false,
                ]
            ])
            ->add('category', 'choice', [
                'choices' => $options['catList'],
                'multiple' => false,
                'expanded' => false,
                'label' => 'Category',
                'placeholder' => false,
            ])
            ->add('highPriorityOnly', 'checkbox', [
                'label' => 'high priority only', 
                'attr' => [
                  'required' => false,
                ],
            ])
            ->add('submit', 'submit', [
                'label' => 'Update', 
                'attr' => [
                    'class' => 'btn-sm btn-success'
                ]
            ]);
    }

    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'catList' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_task_filter';
    }
}
