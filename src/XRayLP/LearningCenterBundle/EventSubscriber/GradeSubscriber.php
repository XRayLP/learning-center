<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 12.01.19
 * Time: 17:14
 */

namespace App\XRayLP\LearningCenterBundle\EventSubscriber;


use App\XRayLP\LearningCenterBundle\Entity\Grade;
use App\XRayLP\LearningCenterBundle\Entity\Thread;
use App\XRayLP\LearningCenterBundle\Event\Events;
use App\XRayLP\LearningCenterBundle\Event\GradeEvent;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\GradeManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class GradeSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    private $flashMessage;

    private $translator;

    private $threadManager;

    private $doctrine;

    private $authorizationChecker;

    private $router;

    private $gradeManager;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag, TranslatorInterface $translator, ThreadManagerInterface $threadManager, RegistryInterface $doctrine, AuthorizationCheckerInterface $authorizationChecker, RouterInterface $router, GradeManager $gradeManager)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashBag;
        $this->translator = $translator;
        $this->threadManager = $threadManager;
        $this->doctrine = $doctrine;
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
        $this->gradeManager = $gradeManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::GRADE_CREATE_SUCCESS_EVENT => 'onCreateSuccess',
            Events::GRADE_PRE_DELETE_EVENT => 'preDelete',
        ];
    }

    public function onCreateSuccess(GradeEvent $event)
    {
        /** @var Grade $grade */
        $grade = $event->getGrade();

        $this->gradeManager->setGrade($grade);

        //create new chat thread for project
        $thread = $this->threadManager->createThread();
        $thread->setSubject('Klassenchat '.$this->gradeManager->getGradeNumber());
        $thread->setCreatedBy($grade->getTutor());
        $thread->setCreatedAt(new \DateTime());

        //save thread
        $this->threadManager->saveThread($thread);
        $grade->setThread($this->doctrine->getRepository(Thread::class)->findOneBy(['id' => $thread->getId()]));
        $this->entityManager->persist($grade);
        $this->entityManager->flush();
    }

    public function preDelete(GradeEvent $event)
    {
        /** @var Grade $grade */
        $grade = $event->getGrade();

        $this->gradeManager->setGrade($grade);

        $this->gradeManager->deleteThread();
    }
}