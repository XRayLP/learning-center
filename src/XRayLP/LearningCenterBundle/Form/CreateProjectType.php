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

    /**
     * Funktion erstellt den Formular Builder, mit dem im Controller dann das Formular generiert werden kann
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //hinzufügen der Formular Felder
        $builder
            ->add('name', TextType::class, array( // Projekt Name
                'label' => 'project.name', // Label für das Feld mit Translator ID
            ))
            ->add('description', TextareaType::class, array( // Projekt Beschreibung
                'label' => 'project.description',
            ))
            // hinzufügen eines Event Listener, der ein Event abhört, welches ausgeführt wird bevor das Formular generiert wird
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
                // derzeitiges FormBuilder Objekt
                $form = $event->getForm();
                // Überprüfung, ob der Nutzer keine Rechte besitzt um das Projekt zu leiten
                if (!$this->authorization->isGranted('project.lead')) {
                    // hinzufügen eines Auswahlfeldes mit dem Member Entity
                    $form->add('leader', EntityType::class, array(
                        'label' => 'project.leader',
                        'class' => Member::class,
                        // Query gibt alle Mitglieder Entities zurück, die Lehrer sind
                        'query_builder' => function (EntityRepository $er) {
                            $teachers = $er->createQueryBuilder('u')
                                ->where("u.memberType = 'ROLE_TEACHER'");
                            // Überprüfung, ob es keine Lehrer gibt
                            if (empty($teachers)) {
                                // Schicken einer Flash Message, die den Nutzer darüber informiert
                                $this->flashMessage->add(
                                    'notice',
                                    array(
                                        'alert' => 'warning',
                                        'title' => '',
                                        'message' => $this->translator->trans('project.create.no.leaders.found', [], 'project')
                                    )
                                );
                            }
                            // Entities werden an das Feld übergeben
                            return $teachers;
                        },
                        'choice_label' => 'lastname', // Nachnamen der Lehrer als Label für das Auswahlfeld
                        'choice_value' => 'id' // ID als Wert

                    ));
                }
            })
            ->add('submit', SubmitType::class, array( // Absende Button für das Formular
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