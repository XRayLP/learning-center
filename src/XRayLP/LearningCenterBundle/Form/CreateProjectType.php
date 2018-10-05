<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Request\CreateProjectRequest;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CreateProjectType extends ContaoAbstractType
{
    private $memberRepository;

    private $authorization;

    private $flashMessage;

    private $translator;

    public function __construct(RegistryInterface $doctrine, AuthorizationCheckerInterface $authorization, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->memberRepository = $doctrine->getRepository(Member::class);
        $this->authorization = $authorization;
        $this->flashMessage = $flashBag;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //form elements
        $builder
            ->add('name', TextType::class, array(
                'label' => 'project.name',
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'project.description',
            ))
            ->add('public', CheckboxType::class, array(
                'label' => 'project.public'
            ))
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){

                $form = $event->getForm();

                if (!$this->authorization->isGranted('project.lead')) {
                    $form->add('leader', EntityType::class, array(
                        'label' => 'project.leader',
                        'class' => Member::class,
                        'query_builder' => function (EntityRepository $er) {
                            $teachers = $er->createQueryBuilder('u')
                                ->where("u.memberType = 'ROLE_TEACHER'");
                            if (empty($teachers)) {
                                $this->flashMessage->add(
                                    'notice',
                                    array(
                                        'alert' => 'warning',
                                        'title' => '',
                                        'message' => $this->translator->trans('project.create.no.leaders.found', [], 'project')
                                    )
                                );
                            }
                            return $teachers;
                        },
                        'choice_label' => 'lastname',
                        'choice_value' => 'id'

                    ));
                }
            })
            ->add('submit', SubmitType::class, array(
                'label' => 'project.continue',
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CreateProjectRequest::class,
            'translation_domain' => 'project'
        ));
        parent::configureOptions($resolver);
    }
}