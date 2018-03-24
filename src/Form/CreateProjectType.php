<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Form;


use Contao\MemberModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use XRayLP\LearningCenterBundle\Entity\Project;

class CreateProjectType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Name',
                'translation_domain' => 'project'
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'translation_domain' => 'project'
            ))
            ->add('groupId', ChoiceType::class, array(
                'choices'   => \System::getContainer()->get('learningcenter.users')->getMemberList(MemberModel::findAll()),
                'multiple'  => 'true',
                'label' => 'Members',
                'translation_domain' => 'project'
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create Project',
                'translation_domain' => 'project'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => Project::class,
            'csrf_protection'   => true,
            'csrf_field_name'   => '_token',
            'csrf_token_id'     => 'project_item'
        ));
    }
}