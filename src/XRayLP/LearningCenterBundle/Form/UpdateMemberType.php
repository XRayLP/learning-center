<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;

use App\XRayLP\LearningCenterBundle\Request\UpdateMemberRequest;
use Contao\System;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateMemberType extends ContaoAbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('dateOfBirth', DateType::class, ['years' => range(date('1970'), date('Y'))])
            ->add('gender', ChoiceType::class, ['choices' => ['male' => 'male', 'female' => 'female']])
            ->add('street', TextType::class)
            ->add('postal', TextType::class)
            ->add('city', TextType::class)
            ->add('state', TextType::class)
            ->add('country', ChoiceType::class, ['choices' => array_flip(System::getCountries())])
            ->add('phone', TextType::class)
            ->add('mobile', TextType::class)
            ->add('fax', TextType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => UpdateMemberRequest::class,
            'required'      => false,
            'empty_data'    => ''
        ));
        parent::configureOptions($resolver);
    }


}