<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Event;


use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use Symfony\Component\EventDispatcher\Event;

class GroupEvent extends Event
{
    /**
     * @var MemberGroup $group
     */
    protected $group;

    /**
     * ProjectEvent constructor.
     * @param MemberGroup $group
     */
    public function __construct(MemberGroup $group)
    {
        $this->group = $group;
    }

    /**
     * @return MemberGroup
     */
    public function getGroup(): MemberGroup
    {
        return $this->group;
    }
}