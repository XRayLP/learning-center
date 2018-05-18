<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;


use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;
use Model\Collection;

class MemberGroup
{
    /**
     * @var MemberGroupModel
     */
    protected $objMemberGroup;

    /**
     * MemberGroup constructor.
     * @param MemberGroupModel|null $objMemberGroup
     */
    public function __construct($objMemberGroup = null)
    {
        if (isset($objMemberGroup)) {
            $this->objMemberGroup = $objMemberGroup;
        } else {
            $this->objMemberGroup = new MemberGroupModel();
        }
    }

    /**
     * @return MemberGroupModel
     */
    public function getGroupModel()
    {
        return $this->objMemberGroup;
    }

    /**
     * @return MemberModel|MemberModel[]|\Contao\Model\Collection|null
     */
    public function getUserModels(){
        $objMembers = MemberModel::findAll();
        while($objMembers->next())
        {
            if (!in_array($this->objMemberGroup->id, StringUtil::deserialize($objMembers->groups))) {
                $objMembers->delete();
            }
        }
        return $objMembers;
    }

    /**
     * Adds a User to a specific group
     *
     * @param MemberModel $objMember
     */
    public function addMemberToGroup($objMember)
    {
        $arrGroups = StringUtil::deserialize($objMember->groups);
        array_push($arrGroups, $this->objMemberGroup->id);
        $objMember->groups = serialize($arrGroups);
        $objMember->save();
    }

    /**
     * @param int $groupType
     */
    public function setGroupType(int $groupType) {
        $this->objMemberGroup->groupType = $groupType;
    }

    /**
     * @return int
     */
    public function getGroupType(): int {
        return $this->objMemberGroup->groupType;
    }
}