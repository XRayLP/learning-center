<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use Contao\MemberGroupModel;
use function PHPSTORM_META\type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Request\ShareFileRequest;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class ShareFileType extends ContaoAbstractType
{

    private $doctrine;

    private $tokenStorage;

    public function __construct(RegistryInterface $doctrine, TokenStorageInterface $tokenStorage)
    {
        $this->doctrine = $doctrine;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @Source(https://symfony.com/doc/current/form/dynamic_form_modification.html#form-events-submitted-data)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', HiddenType::class, array(
                'data_class' => null,
            ))
            ->add('groupType', ChoiceType::class, array(
                'placeholder' => 'Gruppen Typ',
                'choices' => [
                    'Kurse' => 3,
                    'Projekte' => 4
                ],
            ))
            ->add('submit', SubmitType::class)
        ;

        $formModifier = function (FormInterface $form, $groupType) {
            $groups = $this->doctrine->getRepository(MemberGroup::class)->findBy(['groupType' => $groupType]);
            //$groups = $this->doctrine->getRepository(MemberGroup::class)->findAll();

            $form->add('memberGroups', ChoiceType::class, array(
                'data_class' => MemberGroup::class,
                'placeholder' => 'Gruppen',
                'choices' => $groups,
                'choice_value' => 'id',
                'choice_label' => 'name',
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();

                $formModifier($event->getForm(), 3);
            }
        );

        $builder->get('groupType')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $data = $event->getForm()->getData();

                dump($data);

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $data);
            }
        );

        //transforms file object to an integer and reverse
        $builder->get('file')->addModelTransformer(new CallbackTransformer(
            function ($arrToString = null)
            {
                $str = '';
                $index = 0;

                if (isset($arrToString)) {
                    foreach ($arrToString as $file) {
                        if ($file instanceof File) {
                            if ($index == 0) {
                                $str += $file->getId();
                            } else {
                                $str += ',' . $file->getId();
                            }
                            $index++;
                        }
                    }
                }
                return $str;
            },
            function ($strToArray) {
                $files = $this->doctrine->getRepository(File::class)->findBy(['id' => explode(",", $strToArray)]);
                return $files;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ShareFileRequest::class,
            'attr' => ['id' => 'form_share_file']
        ));
        parent::configureOptions($resolver);
    }

    /**
     * @return array|MemberGroup[]
     */
    private function getMemberGroups() {
        $groups = $this->doctrine->getRepository(MemberGroup::class)->findBy(['groupType' => [3, 4]]);
        return $groups;
    }
}