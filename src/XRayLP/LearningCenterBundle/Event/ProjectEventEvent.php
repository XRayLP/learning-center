<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Event;


use App\XRayLP\LearningCenterBundle\Entity\Project;
use Symfony\Component\EventDispatcher\Event;

class ProjectEventEvent extends Event
{
    /**
     * @var Project $project
     */
    private $project;

    /**
     * @var \App\XRayLP\LearningCenterBundle\Entity\Event $event
     */
    private $event;

    public function __construct(Project $project, \App\XRayLP\LearningCenterBundle\Entity\Event $event)
    {
        $this->project = $project;
        $this->event = $event;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return \App\XRayLP\LearningCenterBundle\Entity\Event
     */
    public function getEvent(): \App\XRayLP\LearningCenterBundle\Entity\Event
    {
        return $this->event;
    }


}