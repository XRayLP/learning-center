<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventListener;


use Doctrine\ORM\EntityManager;
use App\XRayLP\LearningCenterBundle\Entity\Calendar;
use App\XRayLP\LearningCenterBundle\Entity\Notification;
use App\XRayLP\LearningCenterBundle\Event\ProjectEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectEventListener
{
    const PROJECT_CREATE_MEMBER_MESSAGE = 'project.create.member.message';
    const PROJECT_CREATE_LEADER_MESSAGE_CONFIRM = 'project.create.leader.message.confirm';
    const PROJECT_CREATE_MEMBER_MESSAGE_NEED_CONFIRM = 'project.create.member.message.need.confirm';

    private $entityManager;

    private $flashMessage;

    private $translator;

    public function __construct(EntityManager $entityManager, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->flashMessage = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @param ProjectEvent $event
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onProjectCreateSuccess(ProjectEvent $event)
    {
        $entityManager = $this->entityManager;

        //every member of the project get a notification
        foreach ($event->getProject()->getGroupId()->getMembers() as $member) {
            $notification = new Notification();
            $notification->setMember($member);
            if (!$event->getProject()->getConfirmed()) {
                if ($member == $event->getProject()->getLeader()) {
                    //project isn't confirmed, you can confirm it
                    $message = $this::PROJECT_CREATE_LEADER_MESSAGE_CONFIRM;
                    $variables['user'] = $event->getProject()->getGroupId()->getMembers()->first()->getFirstname();
                } else {
                    //project isn't confirmed, the leader have to confirm it
                    $message = $this::PROJECT_CREATE_MEMBER_MESSAGE_NEED_CONFIRM;
                }
            } else {
                //projects is already confirmed
                $message = $this::PROJECT_CREATE_MEMBER_MESSAGE;
            }
            $notification->setMessage($message);
            $variables['name'] = $event->getProject()->getName();
            $notification->setVariables($variables);

            $entityManager->persist($notification);
            $entityManager->flush();
        }

        $calendar = new Calendar();
        $calendar->addGroup($event->getProject()->getGroupId());
        $calendar->setTstamp(time());
        $calendar->setTitle('Project: '.$event->getProject()->getName());
        $entityManager->persist($calendar);
        $entityManager->flush();

        $this->flashMessage->add('notice',
            [
                'alert'     => 'success',
                'title'     => '',
                'message'   => $this->translator->trans('project.create.success', [], 'project')
            ]);

    }
}