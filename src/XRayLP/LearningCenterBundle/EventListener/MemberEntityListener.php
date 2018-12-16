<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventListener;


use Colors\RandomColor;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;

class MemberEntityListener
{

    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Member)
        {
            //$entity->setGroups($this->blobStringToArrayCollection($entity->getGroups()));
            $entity->getAvatar();
        }

    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Member)
        {

        }
    }

    protected function arrayCollectionToBlobString(ArrayCollection $arrayCollection): string
    {
        $array = $arrayCollection->toArray();

        $newArray = array();

        foreach ($array as $item)
        {
            if ($item instanceof MemberGroup) {
                $newArray[] = $item->getId();
            }
        }

        return serialize($newArray);
    }

    protected function blobStringToArrayCollection(string $string): ArrayCollection
    {
        $stringArray = \StringUtil::deserialize($string);
        $groups = $this->doctrine->getRepository(MemberGroup::class)->findBy(['id' => $stringArray]);

        $arrayCollection = new ArrayCollection($groups);
        return $arrayCollection;
    }

    protected function checkAvatar(Member $member)
    {
        $path = "images/avatar/";

        if (!file_exists($path) || !is_dir($path)){
            mkdir($path, 0755);
        }

        $existsHQ = file_exists($path . $member->getId() . "_hq.png");
        $exists = file_exists($path . $member->getId() . ".png");
        $color = $this->getGenderColor($member->getGender());

        if (!$exists) {
            $this->createAvatar($member, $color);
        }

        if (!$existsHQ) {
            $this->createAvatar($member, $color, true);
        }
    }

    protected function createAvatar(Member $member, $color ,$highQuality = false)
    {
        if ($highQuality)
        {
            $size = 1000;
            $path = "bundles/learningcenter/avatar/".$member->getId()."_hq.png";
        } else {
            $size = 100;
            $path = "bundles/learningcenter/avatar/" . $member->getId() . ".png";
        }

        $avatar = new InitialAvatar();
        $image = $avatar
            ->name($member->getFirstname()." ".$member->getLastname())
            ->background($color)
            ->color('#fff')
            ->size($size)
            ->generate();
        $image->save($path);

    }

    /**
     * returns a color code based on the user's gender
     * @return string
     */
    public function getGenderColor($gender)
    {
        switch ($gender)
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