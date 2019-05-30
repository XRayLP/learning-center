<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */


namespace App\XRayLP\LearningCenterBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use App\XRayLP\LearningCenterBundle\Entity\Project;

class ProjectEvent extends Event
{
    /**
     * @var Project $project
     */
    protected $project;

    /**
     * ProjectEvent constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }
}