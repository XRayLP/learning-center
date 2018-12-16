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
            ->add('firstname', TextType::class, ['label' => 'firstname'])
            ->add('lastname', TextType::class, ['label' => 'lastname'])
            ->add('dateOfBirth', DateType::class, ['label' => 'date.of.birth', 'years' => range(date('1970'), date('Y'))])
            ->add('gender', ChoiceType::class, ['label' => 'gender', 'choices' => ['gender.male' => 'male', 'gender.female' => 'female']])
            ->add('street', TextType::class, ['label' => 'street'])
            ->add('postal', TextType::class, ['label' => 'postal'])
            ->add('city', TextType::class, ['label' => 'city'])
            ->add('state', TextType::class, ['label' => 'state'])
            ->add('country', ChoiceType::class, ['label' => 'country', 'choices' => array_flip(System::getCountries())])
            ->add('phone', TextType::class, ['label' => 'phone'])
            ->add('mobile', TextType::class, ['label' => 'mobile'])
            ->add('fax', TextType::class, ['label' => 'fax'])
            ->add('password', PasswordType::class, ['label' => 'password'])
            ->add('submit', SubmitType::class, ['label' => 'submit'])
        ;
        dump($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => UpdateMemberRequest::class,
            'required'      => false,
            'empty_data'    => '',
            'translation_domain' => 'member',
        ));
        parent::configureOptions($resolver);
    }


}