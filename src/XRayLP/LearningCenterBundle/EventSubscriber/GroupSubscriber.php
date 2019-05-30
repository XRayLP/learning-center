<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventSubscriber;


use App\XRayLP\LearningCenterBundle\Event\Events;
use App\XRayLP\LearningCenterBundle\Event\GroupEvent;
use App\XRayLP\LearningCenterBundle\Event\ProjectEvent;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\GroupChat;
use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class GroupSubscriber implements EventSubscriberInterface
{


    private $entityManager;

    private $flashMessage;

    private $translator;

    private $threadManager;

    private $doctrine;

    private $authorizationChecker;

    private $router;

    private $chat;

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
        return [
            Events::GROUP_CREATE_SUCCESS_EVENT => 'onGroupCreateSuccess',
        ];
    }

    public function onGroupCreateSuccess(GroupEvent $event)
    {
        $group = $event->getGroup();

        //create the group chat object
    }
}