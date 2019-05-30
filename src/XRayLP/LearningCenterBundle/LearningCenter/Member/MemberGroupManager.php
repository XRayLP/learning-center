<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */


namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use Symfony\Component\Translation\TranslatorInterface;

class MemberGroupManager
{
    protected $translator;

    protected $memberManager;

    /**
     * @var MemberGroup
     */
    protected $memberGroup;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function setMemberGroup(MemberGroup $memberGroup)
    {
        $this->memberGroup = $memberGroup;
    }

    public function getDashboard()
    {

    }

    public function createMemberList()
    {
        $members = $this->getMembers();

        foreach ($members as $member)
        {

        }
    }

    /**
     * @return \App\XRayLP\LearningCenterBundle\Entity\Member[]|\Doctrine\Common\Collections\ArrayCollection
     */
    public function getMembers()
    {
        return $this->memberGroup->getMembers();
    }
}