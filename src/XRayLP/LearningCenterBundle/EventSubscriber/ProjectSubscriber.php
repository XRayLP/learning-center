<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventSubscriber;


use App\XRayLP\LearningCenterBundle\Event\Events;
use App\XRayLP\LearningCenterBundle\Event\ProjectEventEvent;
use App\XRayLP\LearningCenterBundle\Event\ProjectMemberEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectSubscriber implements EventSubscriberInterface
{

    private $entityManager;

    private $flashMessage;

    private $translator;

    public function __construct(EntityManagerInterface $entityManager, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashBag;
        $this->translator = $translator;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::PROJECT_MEMBERS_ADD_SUCCESS_EVENT => 'onMembersAddSuccess',
            Events::PROJECT_MEMBERS_REMOVE_SUCCESS_EVENT => 'onMembersRemoveSuccess',
            Events::PROJECT_MEMBERS_DEGRADE_SUCCESS_EVENT => 'onMembersDegradeSuccess',
            Events::PROJECT_MEMBERS_PROMOTE_LEADER_SUCCESS_EVENT => 'onMembersPromoteToLeaderSuccess',
            Events::PROJECT_MEMBERS_PROMOTE_ADMIN_SUCCESS_EVENT => 'onMembersPromoteToAdminSuccess',
            Events::PROJECT_EVENTS_ADD_SUCCESS_EVENT => 'onEventAddSucces',
            Events::PROJECT_EVENTS_REMOVE_SUCCESS_EVENT => 'onEventRemoveSuccess',
        );
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

    public function onMembersAddSuccess(ProjectMemberEvent $event)
    {
        $member = $event->getMember();
        $project = $event->getProject();

        $message = $this->flashMessage->add('notice', array(
            'alert'     => 'success',
            'title'     => '',
            'message'   => $this->translator->trans('project.members.add.success', ['{member_name}' => ($member->getFirstname().' '.$member->getLastname()), '{project_name}' => $project->getName()], 'project')
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