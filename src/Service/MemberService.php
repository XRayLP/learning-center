<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;


use Contao\FilesModel;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\Model\Collection;
use Contao\StringUtil;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MemberService
{
    /**
     * Creates an array with information about an member
     *
     * @param string $username
     * @return array
     */
    public function getMemberDetails($username)
    {
        $objMember = MemberModel::findByUsername($username);
        if (isset($objMember))
        {
            $avatarImage = $this->getAvatar($objMember);
            $member = array(
                'id'        => $objMember->id,
                'username'  => $objMember->username,
                'firstname' => $objMember->firstname,
                'lastname'  => $objMember->lastname,
                'email'     => $objMember->email,
                'gender'    => $objMember->gender,
                'avatar'    => $avatarImage
            );
            return $member;
        } else {
            throw new NotFoundHttpException("Member with the username '$username' doesn't exist");
        }
    }

    /**
     * Creates a list of members from a collection
     *
     * @param $collection Collection
     * @return mixed
     */
    public function getMemberList($collection)
    {
        $members[] = array();
        foreach ($collection as $objMember)
        {
            $members[$objMember->firstname.' '.$objMember->lastname] = $objMember->id;
        }

        return $members;
    }

    /**
     * Creates a list of member groups form a collection
     *
     * @param $collection Collection
     * @return array
     */
    public function getMemberGroupList($collection)
    {
        $groups[] = array();
        foreach ($collection as $objMemberGroup)
        {
            $groups[$objMemberGroup->name] = $objMemberGroup->id;
        }

        return $groups;
    }

    /**
     * Creates a profile image for the user with the first latter of first and last name
     *
     * @param \MemberModel $objUser
     */
    public function createAvatar($objUser)
    {
        $avatar = new InitialAvatar();
        $image = $avatar
            ->name("$objUser->firstname $objUser->lastname")
            ->background($this->getGenderColor($objUser->gender))
            ->color('#fff')
            ->size(100)
            ->generate();
        $image->save("bundles/learningcenter/avatar/$objUser->id.png");

        //High Quality Image
        $image = $avatar
            ->name("$objUser->firstname $objUser->lastname")
            ->background($this->getGenderColor($objUser->gender))
            ->color('#fff')
            ->size(1000)
            ->generate();
        $image->save("bundles/learningcenter/avatar/".$objUser->id."_hq.png");
        $objUser->avatar = 1;
        $objUser->save();
    }

    /**
     * Returns the Avatar Path from a user
     *
     * @param \MemberModel $objMember
     * @param bool $highQuality
     * @return string
     */
    public function getAvatar($objMember, $highQuality = false)
    {
        if ($objMember->avatar == 0)
        {
            $this->createAvatar($objMember);
        }
        return "/bundles/learningcenter/avatar/".$objMember->id."_hq.png";
    }

    /**
     * Adds a User to a specific group
     *
     * @param MemberModel $objMember
     * @param MemberGroupModel $objGroup
     */
    public function addMemberToGroup($objMember, $objGroup)
    {
        $arrGroups = StringUtil::deserialize($objMember->groups);
        array_push($arrGroups, $objGroup->id);
        $objMember->groups = serialize($arrGroups);
        $objMember->save();
    }

    /**
     * returns a color code based on the user's gender
     *
     * @param string $gender
     * @return string
     */
    public function getGenderColor($gender)
    {
        switch ($gender)
        {
            case 'male':
                $color = '#2E64FE';
                break;

            case 'female':
                $color = '#DF01D7';
                break;

            default:
                $color = '#3ADF00';
                break;
        }

        return $color;
    }
}