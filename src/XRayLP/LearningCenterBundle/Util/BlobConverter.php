<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Util;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;

class BlobConverter
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function arrayCollectionToBlobString(ArrayCollection $arrayCollection)
    {
        $array = $arrayCollection->toArray();

        $newArray = array();

        foreach ($array as $item)
        {
                $newArray[] = $item->getId();
        }

        return serialize($newArray);
    }

    public function blobStringToArrayCollection(string $string, Object $object)
    {
        $stringArray = \StringUtil::deserialize($string);
        $groups = $this->doctrine ->getRepository($object)->findBy(['id' => $stringArray]);

        $arrayCollection = new ArrayCollection($groups);
        return $arrayCollection;
    }
}