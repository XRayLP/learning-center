<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;


use Contao\System;
use FOS\MessageBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReplyMessageFormType extends \FOS\MessageBundle\FormType\ReplyMessageFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('body', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\TextareaType'), array(
                'label' => 'body',
                'attr'  => array(
                    'class' => 'materialize-textarea'
                ),
                'translation_domain' => 'FOSMessageBundle',
            ));
    }

    //configures the standard Request Token for Contao
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_token_manager' => System::getContainer()->get('contao.csrf.token_manager'),
            'csrf_token_id' => System::getContainer()->getParameter('contao.csrf_token_name'),
            'csrf_field_name' => 'REQUEST_TOKEN',
        ));
        parent::configureOptions($resolver);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}