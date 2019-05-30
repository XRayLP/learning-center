<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use Colors\RandomColor;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Symfony\Component\HttpFoundation\File\File;

class Avatar
{
    const path = 'bundles/learningcenter/avatar/';
    const imageContainer = '.png';
    const highResSuffix = '_hq';

    private $member;

    public function __construct(Member $member)
    {
        $this->member = $member;

        //check whether the path exists
        /*if (!is_dir($this::path))
        {
            mkdir($this::path);
        }*/

        if (!file_exists($this::path.$this->member->getId().$this::imageContainer) && !file_exists($this::path.$this->member->getId().$this::highResSuffix.$this::imageContainer)) {
            $this->createAvatar();
        }
    }

    /**
     * @param bool $highRes
     * @return File
     */
    public function getFile(bool $highRes = false)
    {
        if ($highRes == false)
        {
            $file = new File($this::path.$this->member->getId().$this::imageContainer);
            dump($file);
            return $file;
        }
        elseif ($highRes == true)
        {
            $file = new File($this::path.$this->member->getId().$this::highResSuffix.$this::imageContainer);
            return $file;

        }
    }

    /**
     * Creates a profile image for the user with the first latter of first and last name
     */
    public function createAvatar()
    {
        $avatar = new InitialAvatar();
        $color = $this->generateMemberColor();
        $image = $avatar
            ->name($this->member->getFirstname()." ".$this->member->getLastname())
            ->background($color)
            ->color('#fff')
            ->size(100)
            ->generate();
        $image->save($this::path.$this->member->getId().$this::imageContainer);

        //High Quality Image
        $image = $avatar
            ->name($this->member->getFirstname()." ".$this->member->getLastname())
            ->background($color)
            ->color('#fff')
            ->size(1000)
            ->generate();
        $image->save($this::path.$this->member->getId().$this::highResSuffix.$this::imageContainer);
    }

    /**
     * returns a color code based on the user's gender
     * @return string
     */
    private function generateMemberColor()
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
                $color = $this->generateColor();
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

    private function checkDir($path) {
        return is_dir($path);
    }


}