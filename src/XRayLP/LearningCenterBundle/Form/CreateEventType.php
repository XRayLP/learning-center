<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\XRayLP\LearningCenterBundle\Request\CreateEventRequest;

class CreateEventType extends ContaoAbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'project.events.add.name',
            ))
            ->add('startDate', DateType::class, array(
                'label' => 'project.events.add.startDate',
                'widget' => 'choice',
                'input' => 'datetime',
            ))
            ->add('endDate', DateType::class, array(
                'label' => 'project.events.add.endDate',
                'widget' => 'choice',
                'input' => 'datetime',
            ))
            ->add('startTime', TimeType::class, array(
                'label' => 'project.events.add.startTime',
                'widget' => 'choice',
                'input' => 'datetime',

            ))
            ->add('endTime', TimeType::class, array(
                'label' => 'project.events.add.endTime',
                'widget' => 'choice',
                'input' => 'datetime',
            ))
            ->add('address', TextType::class, array(
                'label' => 'project.events.add.address',
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'project.events.add.create',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => CreateEventRequest::class,
            'translation_domain' => 'project'
        ));
        parent::configureOptions($resolver);
    }
}