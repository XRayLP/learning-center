<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use Contao\StringUtil;
use Contao\System;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;

class MemberGroupManagement
{
    protected $memberGroup;

    protected $doctrine;

    public function __construct(MemberGroup $memberGroup)
    {
        $this->doctrine = System::getContainer()->get('doctrine');
        $this->memberGroup = $memberGroup;
    }

    public function getMembers()
    {
        $members = \System::getContainer()->get('doctrine')->getRepository(Member::class)->findAll();
        foreach ($members as $key=>$member) {
            if (!in_array($this->memberGroup->getId(), \StringUtil::deserialize($member->getGroups()))){
                unset($members[$key]);
            }
        }
        return $members;
    }

    /**
     * Adds a project group to the member entity.
     *
     * @param Member $entityMember
     */
    public function add(Member $entityMember){
        $currentGroups = StringUtil::deserialize($entityMember->getGroups());

        //test whether user already has a group
        if (isset($currentGroups)){
            //check whether user is already part of this project
            if (!in_array($this->memberGroup->getId(), $currentGroups)) {
                //add user to project
                array_push($currentGroups, (string)$this->memberGroup->getId());
            }
        } else {
            $currentGroups = array($this->memberGroup->getId());
        }

        //saves the entity in db
        serialize($currentGroups);
        $entityMember->setGroups($currentGroups);
        $this->save($entityMember);
    }

    public function remove(Member $entityMember){
        $currentGroups = $entityMember->getGroups();
        if (isset($currentGroups)){
            $currentGroups = StringUtil::deserialize($currentGroups);
            if ($key = array_search($this->memberGroup->getId(), $currentGroups) !== false){
                unset($currentGroups[$key]);
                $entityMember->setGroups($currentGroups);
                $this->save($entityMember);
            }
        }
    }

    protected function save($entity){
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }
}