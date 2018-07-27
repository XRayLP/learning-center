<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\EventListener;


use Doctrine\ORM\EntityManager;
use XRayLP\LearningCenterBundle\Entity\Notification;
use XRayLP\LearningCenterBundle\Event\ProjectEvent;

class ProjectEventListener
{
    const PROJECT_CREATE_MEMBER_MESSAGE = 'project.create.member.message';
    const PROJECT_CREATE_LEADER_MESSAGE_CONFIRM = 'project.create.leader.message.confirm';
    const PROJECT_CREATE_MEMBER_MESSAGE_NEED_CONFIRM = 'project.create.member.message.need.confirm';

    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ProjectEvent $event
     */
    public function onProjectCreateSuccess(ProjectEvent $event)
    {
        $entityManager = $this->entityManager;

        foreach ($event->getMembers() as $member) {
            $notification = new Notification();
            $notification->setMember($member);
            if (!$event->getProject()->getConfirmed()) {
                if ($member->getId() == $event->getProject()->getLeader()) {
                    $message = $this::PROJECT_CREATE_LEADER_MESSAGE_CONFIRM;
                } else {
                    $message = $this::PROJECT_CREATE_MEMBER_MESSAGE_NEED_CONFIRM;
                }
            } else {
                $message = $this::PROJECT_CREATE_MEMBER_MESSAGE;
            }
            $notification->setMessage($message);

            $entityManager->persist($notification);
            $entityManager->flush();
        }

    }
}