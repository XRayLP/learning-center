<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Request\UpdateUserGroupRequest;

class ChooseUserType extends ContaoAbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('members',EntityType::class, array(
            'multiple' => true,
            'expanded' => true,
            'class'    => Member::class,
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.lastname', 'ASC');
            },
            'choice_value' => 'id',
            'choice_label' => 'lastname',
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Continue',
                'translation_domain' => 'project'
            ))
        ;
    }

    public static function getAllUsers() {
        return \System::getContainer()->get('doctrine')
            ->getRepository(UpdateUserGroupRequest::class)
            ->findAll();
    }
}