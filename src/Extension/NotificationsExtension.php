<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Extension;


use Contao\FrontendUser;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\Translator;
use XRayLP\LearningCenterBundle\Entity\Notification;

class NotificationsExtension extends \Twig_Extension
{
    protected $tokenStorage;

    protected $doctrine;

    protected $translator;

    public function __construct(TokenStorage $tokenStorage, Registry $doctrine, Translator $translator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->doctrine = $doctrine;
        $this->translator = $translator;
    }

    public function getName()
    {
        return 'notification_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_Function('notifications', array($this, 'getNotifications')),
            new \Twig_Function('notificationsCount', array($this, 'countNotifications'))
        );
    }

    public function getNotifications()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof FrontendUser)
        {
            $notifications = $this->doctrine->getRepository(Notification::class)->findBy(array('member' => $user->id));
            //Translation
            foreach ($notifications as $notification)
            {
                $notification->setMessage($this->translator->trans($notification->getMessage()));
            }
            return $notifications;
        }
    }

    public function countNotifications()
    {
        $notifications = $this->getNotifications();

        return count($notifications);
    }
}