<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Project;

use Contao\StringUtil;
use Contao\System;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Entity\Project;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\MemberGroupManagement;

class ProjectMemberManagement extends MemberGroupManagement
{
    protected $entityGroup;

    protected $project;

    protected $doctrine;

    public function __construct(Project $project)
    {
        $this->doctrine = System::getContainer()->get('doctrine');
        $this->entityGroup = $this->doctrine->getRepository(MemberGroup::class)->findOneBy(array('id' => $project->getGroupId()));
        $this->project = $project;
        parent::__construct($this->entityGroup);
    }

    public function get(){

    }

    public function getLeader(){

    }

    public function getAdmins(){

    }

    public function isLeader(Member $entityMember)
    {
        return ($this->isMember($entityMember)) && ($entityMember == $this->project->getLeader());
    }

    public function isAdmin(Member $entityMember)
    {
        return ($this->isMember($entityMember)) && ($this->project->getAdmins()->contains($entityMember));
    }

    public function isMember(Member $entityMember)
    {
        $currentGroups = $entityMember->getGroups();

        //check whether user is already part of this project
        return $currentGroups->contains($this->memberGroup);
    }
}