<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use App\XRayLP\LearningCenterBundle\Entity\Calendar;
use App\XRayLP\LearningCenterBundle\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CalendarController extends AbstractController
{
    private $calendarRepository;
    private $eventRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->calendarRepository = $entityManager->getRepository(Calendar::class);
        $this->eventRepository = $entityManager->getRepository(Event::class);
    }

    /**
     * Searches for Members and returns them as an ajax request.
     *
     * @Route("/learningcenter/calendar/{id}/get", methods={"POST", "GET"}, name="lc_calendar_get", options={"expose"=true})
     * @param Calendar $calendar
     * @param Request $request
     * @return JsonResponse
     */
    public function getEvents(Calendar $calendar, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $eventsArr = [];
            $events = $this->eventRepository->findOneBy(['pid' => $calendar->getId()]);

            foreach ($events as $event)
            {
                if ($event instanceof Event)
                {
                    $event->setEndTime($event->getEndTime()*1000);
                    $event->setStartTime($event->getStartTime()*1000);
                }
            }


            if (!empty($events)) {
                $encoders = [
                    new JsonEncoder(),
                ];
                $normalizer = [
                    (new ObjectNormalizer())
                        ->setIgnoredAttributes([
                            'pid', 'tstamp', 'alias', 'teaser', 'addImage', 'overwriteMeta', 'singleSRC', 'alt', 'imageTitle', 'size', 'imagemargin', 'imageUrl', 'fullsize', 'caption', 'floating', 'addEnclosure', 'enclosure', 'orderEnclosure', 'source', 'jumpTo', 'articleId', 'url', 'target', 'cssClass', 'noComments', 'published', 'start', 'stop'
                        ])

                ];
                $serializer = new Serializer($normalizer, $encoders);

                $data = $serializer->serialize($events, 'json', array('attributes' => ['title', 'startTime', 'endTime']));

                return new JsonResponse($data, 200, [], true);
            }
            return new JsonResponse([
                'type' => 'error',
                'message' => 'TEST',
            ]);
        }

        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only',
        ]);
    }
}