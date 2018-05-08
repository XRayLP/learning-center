<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Form;


use Contao\MemberGroupModel;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ShareFileType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('sharedGroups', ChoiceType::class, array(
                'choices' => $this->getMemberGroups()
            ))
            ->add('submit', SubmitType::class)
        ;
    }

    private function getMemberGroups() {
        $objGroups = MemberGroupModel::findBy('groupType', 5);
        if ($objGroups === null) {
            throw new \Exception("User has no groups!");
        } else {
            $groups[] = array();
            foreach ($objGroups as $objGroup)
            {
                $groups[$objGroup->name] = $objGroup->id;
            }
            return $groups;
        }
    }
}