<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Request;


use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;

class ShareFileRequest
{
    private $file;

    private $groupType;

    private $memberGroups;

    /**
     * @return File[]
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File[] $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getMemberGroups()
    {
        return $this->memberGroups;
    }

    /**
     * @param MemberGroup[] $memberGroup
     */
    public function setMemberGroups($memberGroup)
    {
        $this->memberGroups = $memberGroup;
    }

    /**
     * @return mixed
     */
    public function getGroupType()
    {
        return $this->groupType;
    }

    /**
     * @param mixed $groupType
     */
    public function setGroupType($groupType): void
    {
        $this->groupType = $groupType;
    }





}