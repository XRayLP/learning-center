<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;


class CourseGroup extends MemberGroup
{
    public function __construct($objMemberGroup = null)
    {
        parent::__construct($objMemberGroup);

        if (!isset($objMemberGroup)) {
            $this->objMemberGroup->type = 5;
        }
    }
}