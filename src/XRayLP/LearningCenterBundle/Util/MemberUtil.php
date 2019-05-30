<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Util;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use Doctrine\ORM\EntityManagerInterface;

class MemberUtil
{
    private $memberRepository;

    private $groupRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->memberRepository = $entityManager->getRepository(Member::class);
        $this->groupRepository = $entityManager->getRepository(MemberGroup::class);
    }

    /**
     * Gets all registered Members and returns them in an Array[$id] = 'firstname lastname'
     *
     * @return array
     */
    public function getAllMembers()
    {
        $members = $this->memberRepository->findAll();

        $arrMembers = array();

        foreach ($members as $member)
        {
            $arrMembers[$member->getId()] = $member->getFirstname().' '.$member->getLastname();
        }

        return $arrMembers;
    }

    public function getAllTeachers()
    {
        $members = $this->memberRepository->findBy(array('memberType' => 'ROLE_TEACHER'));

        $arrMembers = array();

        foreach ($members as $member)
        {
            $arrMembers[$member->getId()] = $member->getFirstname().' '.$member->getLastname();
        }

        return $arrMembers;
    }

    /**
     * Gets all registered Groups and returns them in an Array[$id] = 'name'
     *
     * @return array
     */
    public function getAllGroups()
    {
        $groups = $this->groupRepository->findAll();

        $arrGroups = array();

        foreach ($groups as $group)
        {
            $arrGroups[$group->getId()] = $group->getName();
        }

        return $arrGroups;
    }
}