<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Project;

use Contao\StringUtil;
use Contao\System;
use XRayLP\LearningCenterBundle\Entity\Member;
use XRayLP\LearningCenterBundle\Entity\MemberGroup;
use XRayLP\LearningCenterBundle\Entity\Project;
use XRayLP\LearningCenterBundle\Member\MemberGroupManagement;

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

    public function isLeader(Member $entityMember){
        return ($this->isMember($entityMember)) && ($entityMember->getId() == $this->project->getLeader());
    }

    public function isAdmin(Member $entityMember){
        $admins = StringUtil::deserialize($this->project->getAdmins());
        if (!is_array($admins)) {
            return false;
        }
        return ($this->isMember($entityMember)) && (in_array($entityMember->getId(), $admins));
    }

    public function isMember(Member $entityMember){
        $currentGroups = StringUtil::deserialize($entityMember->getGroups());

        //check whether user is already part of this project
        return in_array($this->entityGroup->getId(), $currentGroups);

    }
}