<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use Colors\RandomColor;
use Contao\FrontendUser;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Model\Collection;

class FrontendMember
{
    /**
     * @var MemberModel|FrontendUser
     */
    protected $objMember;

    public function __construct($objMember)
    {
        $this->objMember = $objMember;
    }

    /**
     * @return MemberModel
     */
    public function getUserModel()
    {
        return $this->objMember;
    }

    public function getMemberGroups(){
        return MemberGroupModel::findMultipleByIds(StringUtil::deserialize($this->objMember->groups));
    }

    /**
     * @return null|Permissions
     * Get a Right Object for User
     */
    public function getRights(){
        /*$objMemberGroups = $this->getMemberGroups();
        foreach ($objMemberGroups as $objGroup) {
            if ($objGroup->type == 2){
                $objRightGroup = $objGroup;
                return new Rights(new RightGroup($objRightGroup));
            }
        }*/
        return new Permissions(new PermissionsGroup(MemberGroupModel::findById(1)));
    }

    /**
     * Returns an array of projects, of which a user is part of
     *
     * @return MemberGroupModel|Collection
     */
    public function getProjects()
    {
        $objProjects = MemberGroupModel::findBy('groupType', 5);
        /*if ($objProjects !== null) {
            while ($objProjects->next()) {
                if (in_array($objProjects->id, StringUtil::deserialize($this->objMember->groups))) {
                    $objProjects->delete();
                }

            }
        }*/
        return $objProjects;
    }

    /**
     * Adds a User to a specific group
     *
     * @param MemberGroupModel $objGroup
     */
    public function addGroup($objGroup)
    {
        $arrGroups = StringUtil::deserialize($this->objMember->groups);
        array_push($arrGroups, $objGroup->id);
        $this->objMember->groups = serialize($arrGroups);
        $this->objMember->save();
    }

    /**
     * Returns the Avatar Path from a user
     *
     * @param bool $highQuality
     * @return string
     */
    public function getAvatar($highQuality = false): string
    {
        if (!$this->isAvatar($highQuality)) {
            $this->createAvatar();
        }

        if ($highQuality) {
            return "/bundles/learningcenter/avatar/" . $this->objMember->id . "_hq.png";
        } else {
            return "/bundles/learningcenter/avatar/" . $this->objMember->id . ".png";
        }
    }

    private function isAvatar($highQuality = false): bool
    {
        if ($highQuality) {
            $exists = file_exists("bundles/learningcenter/avatar/" . $this->objMember->id . "_hq.png");
        } else {
            $exists = file_exists("bundles/learningcenter/avatar/" . $this->objMember->id . ".png");
        }
        return $exists;
    }

    /**
     * Creates a profile image for the user with the first latter of first and last name
     */
    public function createAvatar()
    {
        $avatar = new InitialAvatar();
        $color = $this->getGenderColor();
        $image = $avatar
            ->name($this->objMember->firstname." ".$this->objMember->lastname)
            ->background($color)
            ->color('#fff')
            ->size(100)
            ->generate();
        $image->save("bundles/learningcenter/avatar/".$this->objMember->id.".png");

        //High Quality Image
        $image = $avatar
            ->name($this->objMember->firstname." ".$this->objMember->lastname)
            ->background($color)
            ->color('#fff')
            ->size(1000)
            ->generate();
        $image->save("bundles/learningcenter/avatar/".$this->objMember->id."_hq.png");
        $this->objMember->avatar = 1;
        $this->objMember->save();
    }

    /**
     * returns a color code based on the user's gender
     * @return string
     */
    public function getGenderColor()
    {
        switch ($this->objMember->gender)
        {
            case 'male':
                $color = $this->generateColor(array('blue', 'green', 'yellow', 'red'));
                break;

            case 'female':
                $color = $this->generateColor(array('purple', 'pink', 'yellow', 'red'));
                break;

            default:
                $color = '#3ADF00';
                break;
        }

        return $color;
    }

    private function generateColor($colorPerence = null) {
        if (isset($colorPerence)) {
            return RandomColor::one(array(
                'luminosity' => 'dark',
                'hue' => $colorPerence
            ));
        } else {
            return RandomColor::one(array(
                'luminosity'    => 'dark'
            ));
        }
    }
}