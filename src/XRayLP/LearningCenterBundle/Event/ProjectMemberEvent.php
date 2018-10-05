<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 29.09.2018
 * Time: 11:43
 */

namespace App\XRayLP\LearningCenterBundle\Event;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;
use Symfony\Component\EventDispatcher\Event;

class ProjectMemberEvent extends Event
{
    /**
     * @var Project $project
     */
    private $project;

    /**
     * @var Member $member
     */
    private $member;

    /**
     * ProjectEvent constructor.
     *
     * @param Project $project
     * @param Member $member
     */
    public function __construct(Project $project, Member $member)
    {
        $this->project = $project;
        $this->member = $member;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
    }
}