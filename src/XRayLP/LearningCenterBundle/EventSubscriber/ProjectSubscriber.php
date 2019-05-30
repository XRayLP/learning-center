<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventSubscriber;


use App\XRayLP\LearningCenterBundle\Entity\Calendar;
use App\XRayLP\LearningCenterBundle\Entity\Event;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Message;
use App\XRayLP\LearningCenterBundle\Entity\MessageMetadata;
use App\XRayLP\LearningCenterBundle\Entity\ThreadMetadata;
use App\XRayLP\LearningCenterBundle\Event\Events;
use App\XRayLP\LearningCenterBundle\Event\ProjectEvent;
use App\XRayLP\LearningCenterBundle\Event\ProjectEventEvent;
use App\XRayLP\LearningCenterBundle\Event\ProjectMemberEvent;
use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectSubscriber implements EventSubscriberInterface
{

    private $entityManager;

    private $flashMessage;

    private $translator;

    private $threadManager;

    private $doctrine;

    private $authorizationChecker;

    private $router;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag, TranslatorInterface $translator, ThreadManagerInterface $threadManager, RegistryInterface $doctrine, AuthorizationCheckerInterface $authorizationChecker, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashBag;
        $this->translator = $translator;
        $this->threadManager = $threadManager;
        $this->doctrine = $doctrine;
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
    }

    /**
     * Gibt ein Array von Event Namen zurück, die diese Klasse abhört.
     * Jedem Event Namen wird eine Funktion zugewiesen, die ausgeführt wird, wenn das Event ausgelöst wird
     *
     * @return array Die Event Namen
     */
    public static function getSubscribedEvents()
    {
        return array(
            // wenn ein Mitglied zu einem Projekt hinzugefügt wird
            Events::PROJECT_MEMBERS_ADD_SUCCESS_EVENT => 'onMembersAddSuccess',
            // wenn ein Mitglied aus einem Projekt entfernt wird
            Events::PROJECT_MEMBERS_REMOVE_SUCCESS_EVENT => 'onMembersRemoveSuccess',
            // wenn ein Mitglied im Projekt Rang degradiert wird
            Events::PROJECT_MEMBERS_DEGRADE_SUCCESS_EVENT => 'onMembersDegradeSuccess',
            // wenn ein Mitglied zum Projektleiter befördert wird
            Events::PROJECT_MEMBERS_PROMOTE_LEADER_SUCCESS_EVENT => 'onMembersPromoteToLeaderSuccess',
            // wenn ein Mitglied zum Admin befördert wird
            Events::PROJECT_MEMBERS_PROMOTE_ADMIN_SUCCESS_EVENT => 'onMembersPromoteToAdminSuccess',
            // wenn einem Projekt ein Termin hinzugefügt wird
            Events::PROJECT_EVENTS_ADD_SUCCESS_EVENT => 'onEventAddSucces',
            // wenn ein Termin aus einem Projekt entfernt wird
            Events::PROJECT_EVENTS_REMOVE_SUCCESS_EVENT => 'onEventRemoveSuccess',
            // wenn ein Projekt editiert wird
            Events::PROJECT_UPDATE_SUCCESS_EVENT => 'onProjectUpdateSuccess',
            // bevor ein Projekt gelöscht wird
            Events::PROJECT_PRE_DELETE_EVENT => 'preProjectDelete',
            // nachdem ein Projekt gelöscht wird
            Events::PROJECT_POST_DELETE_SUCCESS_EVENT => 'postProjectDeleteSuccess',
            // bevor ein Projekt geladen wird
            Events::PROJECT_PRE_LOAD => 'preProjectLoad',
        );
    }

    /**
     * Überprüft bevor ein Projekt geladen wird, ob es von einem Leiter bestätigt wurde.
     *
     * @param ProjectEvent $event enthält das jeweilige Projekt Objekt
     */
    public function preProjectLoad(ProjectEvent $event)
    {
        // Projekt Objekt, des zu überprüfenden Projektes
        $project = $event->getProject();

        // Überprüfung, ob das Projekt noch nicht bestätigt wurde
        if (!$project->getConfirmed())
        {
            // Generierung einer Nachricht, die darüber informiert, dass noch eine Bestätigung benötigt wird
            $message = (
                $this->authorizationChecker->isGranted('project.confirm', $project) ? // ist der Nutzer berechtigt ein Projekt zu bestätigen?
                $this->translator->trans('project.confirm.now')  : // Ja: Nachricht mit Bestätigungs Aufforderung wird über den Translator generiert
                $this->translator->trans('project.need.confirm') // Nein: Nachricht mit Hinweisen wird über den Translator generiert
            );

            // Erstellung einer Flash Message (wird beim nächsten Laden der Webseite im Hauptbereich des LMS eingeblendet)
            $this->flashMessage->add(
                'notice', // Typ -> Nutzung eines best. Templates
                array(
                    'alert' => 'info', // Bootstrap/Materialize Alarmtyp
                    'title' => '', // Titel der Alarmbox
                    'message' => $message, // oben generierte Nachricht
                    'href' => $this->router->generate('lc_projects_confirm', ['id' => $project->getId()]) // Bestätigungslink
                )
            );
        }
    }

    public function postProjectDeleteSuccess(ProjectEvent $event)
    {
        $project = $event->getProject();

        $project = $event->getProject();
        $message = $this->flashMessage->add('notice', array(
            'alert' => 'success',
            'title' => '',
            'message' => $this->translator->trans('project.delete.success', ['{name}' => $project->getName()], 'project')
        ));
    }

    public function preProjectDelete(ProjectEvent $event)
    {
        //to delete
        $project = $event->getProject();
        $memberGroup = $project->getGroupId();
        $calendar = $this->doctrine->getRepository(Calendar::class)->findOneByGroup($memberGroup);
        $thread = $project->getThread();

        //delete thread
        if (isset($thread))
        {
            $messages = $this->doctrine->getRepository(Message::class)->findBy(['thread' => $thread]);

            foreach ($messages as $message)
            {
                //delete all metadata of message
                $message_metadata = $this->doctrine->getRepository(MessageMetadata::class)->findBy(['message' => $message]);
                foreach ($message_metadata as $metadata)
                {
                    $this->entityManager->remove($metadata);
                    $this->entityManager->persist($metadata);
                    $this->entityManager->flush();
                }

                //delete message
                $this->entityManager->remove($message);
                $this->entityManager->persist($message);
                $this->entityManager->flush();
            }

            $thread_metadata = $this->doctrine->getRepository(ThreadMetadata::class)->findBy(['thread' => $thread]);

            //delete all metadata
            foreach ($thread_metadata as $metadata)
            {
                $this->entityManager->remove($metadata);
                $this->entityManager->persist($metadata);
                $this->entityManager->flush();
            }

            //delete thread
            $this->entityManager->remove($thread);
            $this->entityManager->persist($thread);
            $this->entityManager->flush();
        }

        //calendar
        if (isset($calendar))
        {
            $events = $this->doctrine->getRepository(Event::class)->findBy(['pid' => $calendar]);
            if (isset($event))
            {
                //remove all calendar events
                foreach ($events as $event)
                {
                    $this->entityManager->remove($event);
                    $this->entityManager->persist($event);
                    $this->entityManager->flush();
                }
            }
            //remove calendar
            $this->entityManager->remove($calendar);
            $this->entityManager->persist($calendar);
            $this->entityManager->flush();
        }


        //memberGroup
        if (isset($memberGroup))
        {
            $members = $memberGroup->getMembers()->toArray();
            if (isset($members))
            {
                //remove group from each member
                foreach ($members as $member)
                {
                    if ($member instanceof Member)
                    {
                        $member->removeGroup($memberGroup);
                    }
                }
            }

            //remove member group
            $this->entityManager->remove($memberGroup);
            $this->entityManager->persist($memberGroup);
            $this->entityManager->flush();
        }



    }

    public function onProjectUpdateSuccess(ProjectEvent $event)
    {
        $project = $event->getProject();
        $message = $this->flashMessage->add('notice', array(
            'alert' => 'success',
            'title' => '',
            'message' => $this->translator->trans('project.update.success', ['{name}' => $project->getName()], 'project')
        ));
    }

    public function onEventAddSucces(ProjectEventEvent $event)
    {
        $project = $event->getProject();
        $calEvent = $event->getEvent();

        $message = $this->flashMessage->add('notice', array(
            'alert'     => 'success',
            'title'     => '',
            'message'   => $this->translator->trans('project.events.add.success', ['{event_name}' => $calEvent->getTitle(), '{project_name}' => $project->getName()], 'project')

        ));
    }

    public function onEventRemoveSuccess(ProjectEventEvent $event)
    {
        $project = $event->getProject();
        $calEvent = $event->getEvent();

        $message = $this->flashMessage->add('notice', array(
            'alert'     => 'success',
            'title'     => '',
            'message'   => $this->translator->trans('project.events.remove.success', ['{event_name}' => $calEvent->getTitle(), '{project_name}' => $project->getName()], 'project')

        ));
    }

    /**
     * Fügt neues Mitglied zum Projektchat hinzu und veschickt eine Flash Message zum Mitglied
     *
     * @param ProjectMemberEvent $event enthält Project und Member Objekt
     */
    public function onMembersAddSuccess(ProjectMemberEvent $event)
    {
        // hinzugefügtes Mitglied Entity
        $member = $event->getMember();

        // Projekt Entity
        $project = $event->getProject();

        // Thread Objekt, des jeweiligen Projektes
        $thread = $project->getThread();

        // hinzufügen des neuen  Mitglieds zum Chat
        $thread->addParticipant($member);

        // Änderungen speichern
        $this->threadManager->saveThread($thread);

        // Erstellung einer Flash Message (wird beim nächsten Laden der Webseite im Hauptbereich des LMS eingeblendet)
        $message = $this->flashMessage->add(
            'notice', // Typ -> Nutzung eines best. Templates
            array(
            'alert'     => 'success', // grüner Alarm
            'title'     => '',
            // Nachricht an das neu hinzugefügte Mitglied wird über den Translator Service generiert
            'message'   => $this->translator->trans(
                'project.members.add.success', // Übersetzungs ID, die die Übersetzung speichert
                [ // Variablen der Übersetzung werde befüllt
                    '{member_name}' => ($member->getFirstname().' '.$member->getLastname()), // Mitgliedsname
                    '{project_name}' => $project->getName() // Projektname
                ],
                'project') // Übersetzungsdomain: gibt an in welcher Datei die Übersetzungen gespeichert sind
        ));
    }



    public function onMembersDegradeSuccess(ProjectMemberEvent $event)
    {
        $member = $event->getMember();
        $project = $event->getProject();

        $message = $this->flashMessage->add('notice', array(
            'alert'     => 'success',
            'title'     => '',
            'message'   => $this->translator->trans('project.members.degrade.success.message', ['{member_name}' => ($member->getFirstname().' '.$member->getLastname()), '{project_name}' => $project->getName()], 'project')
        ));
    }

    public function onMembersPromoteToAdminSuccess(ProjectMemberEvent $event)
    {
        $member = $event->getMember();
        $project = $event->getProject();

        $message = $this->flashMessage->add('notice', array(
            'alert'     => 'success',
            'title'     => '',
            'message'   => $this->translator->trans('project.members.promote.admin.success.message', ['{member_name}' => ($member->getFirstname().' '.$member->getLastname()), '{project_name}' => $project->getName()], 'project')
        ));
    }

    public function onMembersPromoteToLeaderSuccess(ProjectMemberEvent $event)
    {
        $member = $event->getMember();
        $project = $event->getProject();

        $message = $this->flashMessage->add('notice', array(
            'alert'     => 'success',
            'title'     => '',
            'message'   => $this->translator->trans('project.members.promote.leader.success.message', ['{member_name}' => ($member->getFirstname().' '.$member->getLastname()), '{project_name}' => $project->getName()], 'project')
        ));
    }

    public function onMembersRemoveSuccess(ProjectMemberEvent $event)
    {
        $member = $event->getMember();
        $project = $event->getProject();

        $message = $this->flashMessage->add('notice', array(
            'alert'     => 'success',
            'title'     => '',
            'message'   => $this->translator->trans('project.members.remove.success.message', ['{member_name}' => ($member->getFirstname().' '.$member->getLastname()), '{project_name}' => $project->getName()], 'project')
        ));
    }
}