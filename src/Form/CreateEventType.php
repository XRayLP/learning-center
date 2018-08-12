<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use XRayLP\LearningCenterBundle\Request\CreateEventRequest;

class CreateEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Name',
                'translation_domain' => 'project'
            ))
            ->add('startDate', DateType::class, array(
                'label' => 'startDate',
                'widget' => 'choice',
                'input' => 'datetime',
                'translation_domain' => 'project'
            ))
            ->add('endDate', DateType::class, array(
                'label' => 'endDate',
                'widget' => 'choice',
                'input' => 'datetime',
                'translation_domain' => 'project'
            ))
            ->add('startTime', TimeType::class, array(
                'label' => 'startTime',
                'widget' => 'choice',
                'input' => 'datetime',
                'translation_domain' => 'project'
            ))
            ->add('endTime', TimeType::class, array(
                'label' => 'endTime',
                'widget' => 'choice',
                'input' => 'datetime',
                'translation_domain' => 'project'
            ))
            ->add('address', TextType::class, array(
                'label' => 'address',
                'translation_domain' => 'project'
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Continue',
                'translation_domain' => 'project'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => CreateEventRequest::class,
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'event_item'
        ));
    }
}