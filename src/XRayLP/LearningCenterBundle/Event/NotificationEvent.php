<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use App\XRayLP\LearningCenterBundle\Entity\Notification;

class NotificationEvent extends Event
{
    const NAME = 'notification.add';

    /**
     * @var Notification $notification
     */
    private $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function getNotification(): Notification
    {
        return $this->notification;
    }

    public function getMember()
    {
        return $this->notification->getMember();
    }
}