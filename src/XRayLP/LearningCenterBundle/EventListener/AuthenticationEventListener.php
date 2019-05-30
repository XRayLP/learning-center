<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventListener;


use Contao\FrontendUser;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use App\XRayLP\LearningCenterBundle\Entity\Notification;

class AuthenticationEventListener
{
    protected $doctrine;

    protected $twig;

    protected $notifications;

    public function __construct(\Twig_Environment $twig, RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;

        $this->twig = $twig;
    }

    /**
     * @return Notification[]
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof FrontendUser)
        {
            $this->notifications = $this->doctrine->getRepository(Notification::class)->findBy(array('member' => $user->id));
        }
    }
}