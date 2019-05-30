<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;

use Colors\RandomColor;
use Contao\StringUtil;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;

class MemberManagement
{

    private $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function isMemberOf(MemberGroup $memberGroup) {
        if ($this->member->getGroups()->contains($memberGroup)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the Avatar Path from a user
     *
     * @param bool $highQuality
     * @return string
     */
    public function getAvatar($highQuality = false): string
    {
        if ($highQuality) {
            return "/bundles/learningcenter/avatar/" . $this->member->getId() . "_hq.png";
        } else {
            return "/bundles/learningcenter/avatar/" . $this->member->getId() . ".png";
        }
    }

    private function isAvatar($highQuality = false): bool
    {
        if ($highQuality) {
            $exists = file_exists("bundles/learningcenter/avatar/" . $this->member->getId() . "_hq.png");
        } else {
            $exists = file_exists("bundles/learningcenter/avatar/" . $this->member->getId() . ".png");
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
            ->name($this->member->getFirstname()." ".$this->member->getLastname())
            ->background($color)
            ->color('#fff')
            ->size(100)
            ->generate();
        $image->save("bundles/learningcenter/avatar/".$this->member->getId().".png");

        //High Quality Image
        $image = $avatar
            ->name($this->member->getFirstname()." ".$this->member->getLastname())
            ->background($color)
            ->color('#fff')
            ->size(1000)
            ->generate();
        $image->save("bundles/learningcenter/avatar/".$this->member->getId()."_hq.png");
        $this->member->setAvatar(1);
        $entityManager = \System::getContainer()->get('doctrine')->getManager();
        $entityManager->persist($this);
        $entityManager->flush();
    }

    /**
     * returns a color code based on the user's gender
     * @return string
     */
    public function getGenderColor()
    {
        switch ($this->member->getGender())
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