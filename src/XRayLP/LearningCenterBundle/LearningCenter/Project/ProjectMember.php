<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Project;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\MemberManagement;

class ProjectMember
{
    private $project;

    private $member;

    public function __construct(Project $project, Member $member)
    {
        $this->project = $project;
        $this->member = $member;
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @return MemberManagement
     */
    public function getMemberManagement(): MemberManagement
    {
        return (new MemberManagement($this->member));
    }

    //helper functions
    public function isLeader()
    {
        return ($this->isMember() && ($this->member == $this->project->getLeader()));
    }

    public function isAdmin()
    {
        return ($this->isMember() && ($this->project->getAdmins()->contains($this->member)));
    }

    public function isProjectMember()
    {
        return $this->isMember();
    }

    public function isMember()
    {
        $currentGroups = $this->member->getGroups();

        //check whether user is already part of this project
        return $currentGroups->contains($this->project->getGroupId());
    }


}