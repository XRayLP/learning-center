<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 02.07.2018
 * Time: 12:46
 */

namespace App\XRayLP\LearningCenterBundle\Request;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Member\MemberGroupManagement;

class UpdateUserGroupRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=50)
     */
    private $currentMembers;

    private $removedMembers;

    private $addedMembers;


    public static function fromMemberGroup(MemberGroup $userGroup): self
    {
        $memberGroupManagement = new MemberGroupManagement($userGroup);

        $projectRequest = new self();
        $projectRequest->setMembers($memberGroupManagement->getMembers());

        return $projectRequest;
    }

    /**
     * @return mixed
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param mixed $name
     */
    public function setMembers($name)
    {
        $this->members = $name;
    }

}