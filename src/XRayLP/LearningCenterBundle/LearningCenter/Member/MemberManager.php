<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;

class MemberManager
{
    /**
     * @var Member $member
     */
    private $member;

    public function setMember(Member $member)
    {
        $this->member = $member;
    }

    public function getAvatar()
    {
        if (isset($this->member))
        {
            return (new Avatar($this->member));
        } else {return null;}

    }

    public function isMemberOf(MemberGroup $memberGroup) {
        if ($this->member->getGroups()->contains($memberGroup)){
            return true;
        } else {
            return false;
        }
    }


}