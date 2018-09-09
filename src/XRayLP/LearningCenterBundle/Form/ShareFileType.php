<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;


use Contao\MemberGroupModel;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Request\ShareFileRequest;

class ShareFileType extends AbstractType
{

    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', HiddenType::class, array(
                'data_class' => null,
            ))
            ->add('memberGroups', ChoiceType::class, array(
                'data_class' => MemberGroup::class,
                'choices' => $this->getMemberGroups(),
                'choice_value' => 'id',
                'choice_label' => 'courseName',
            ))
            ->add('submit', SubmitType::class)
        ;

        $builder->get('file')->addModelTransformer(new CallbackTransformer(
            function (File $file = null) {return $file? $file->getId():0;},
            function ($course = null) {return $this->doctrine->getRepository(File::class)->findOneById($course);}
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ShareFileRequest::class,
        ));
    }

    /**
     * @return array|MemberGroup[]
     */
    private function getMemberGroups() {
        $groups = $this->doctrine->getRepository(MemberGroup::class)->findByType(3);
        return $groups;
    }
}