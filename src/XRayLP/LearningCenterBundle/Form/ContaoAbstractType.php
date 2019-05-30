<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;


use Contao\System;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContaoAbstractType extends AbstractType
{

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