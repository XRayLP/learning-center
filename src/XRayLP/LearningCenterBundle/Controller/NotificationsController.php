<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use Contao\FrontendUser;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\XRayLP\LearningCenterBundle\Entity\Notification;

class NotificationsController extends AbstractController
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function ajaxLoadAction(Request $request)
    {
        $jsonData = array();
        $idx = 0;

        $user = $request->getUser();

        if ($user instanceof FrontendUser)
        {
            $notifications = $this->doctrine->getRepository(Notification::class)->findBy(array('member' => $user->id));

            $notifications = $this->doctrine->getRepository(Notification::class)->findAll();
            foreach ($notifications as $notification)
            {
                $tmp = array(
                    'id' => $notification->getId(),
                    'message' => $notification->getMessage(),
                    'icon'  => $notification->getIcon(),
                );
                $jsonData[$idx++] = $tmp;
            }

        }


        return new JsonResponse($jsonData);
    }
}