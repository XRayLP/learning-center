<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;

use Contao\MemberGroupModel;

class Project extends MemberGroup
{

    /**
     * Project constructor.
     *
     * @param MemberGroupModel|null $objMemberGroup
     */
    public function __construct($objMemberGroup = null)
    {
        parent::__construct($objMemberGroup);

        if (!isset($objMemberGroup)) {
            $this->setGroupType(6);
        }
    }

    /**
     * @param string $name
     */
    public function setProjectName(string $name) {
        $this->objMemberGroup->projectName = $name;
    }

    /**
     * @return string
     */
    public function getProjectName(): string {
        return $this->objMemberGroup->projectName;
    }

    /**
     * @param string $description
     */
    public function setProjectDescription(string $description) {
        $this->objMemberGroup->projectDescription = $description;
    }

    /**
     * @return string
     */
    public function getProjectDescription(): string {
        return $this->objMemberGroup->projectDescription;
    }

    /**
     * Creates an array with information about a project
     *
     * @return array
     */
    public function getProjectDetails()
    {
        $members = array();
        $objMembers = $this->getUserModels();
        foreach ($objMembers  as $objMember)
        {
            $member = new Member($objMember);
            $members[] = array(
                'firstname' => $objMember->firstname,
                'lastname'  => $objMember->lastname,
                'avatar'    => $member->getAvatar(),
                'url'       => \System::getContainer()->get('router')->generate('learningcenter_user.details', array('username' => $objMember->username))
            );
        }

        //array
        return $project = array(
            'id'            => $this->objMemberGroup->id,
            'name'          => $this->getProjectName(),
            'description'   => $this->getProjectDescription(),
            'members'       => $members
        );
    }
}