<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\EventListener;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use XRayLP\LearningCenterBundle\Entity\Member;
use XRayLP\LearningCenterBundle\Entity\MemberGroup;

class MemberGroupEntityListener
{
    protected $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof MemberGroup)
        {
            $this->saveAllUserGroups($entity);
        }
    }

    private function saveAllUserGroups(MemberGroup $memberGroup)
    {
        $members = $memberGroup->getMembers()->toArray();

        //check for each member of the group, whether he was added in the current instance of this entity or before
        foreach ($members as $member)
        {
            if ($member instanceof Member)
            {
                // if he was -> update his groups
                if (!$member->getGroups()->contains($memberGroup)) {
                    $member->addGroup($memberGroup);

                    $entityManager = $this->doctrine->getManager();
                    $entityManager->persist($member);
                    $entityManager->flush();
                }
            }
        }
    }

}