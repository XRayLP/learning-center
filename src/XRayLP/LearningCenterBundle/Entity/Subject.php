<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Subject
 * @package App\XRayLP\LearningCenterBundle\Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\StandardRepository")
 * @ORM\Entity()
 * @ORM\Table(name="tl_subject", options={"engine":"InnoDB"})
 */
class Subject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tstamp;

    /**
     * @ORM\Column(type="string", options={"default":""})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Member")
     */
    private $leader;

    /**
     * @ORM\OneToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\MemberGroup")
     */
    private $group;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * @param mixed $tstamp
     */
    public function setTstamp($tstamp): void
    {
        $this->tstamp = $tstamp;
    }



    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return Member
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * @param Member $leader
     */
    public function setLeader(Member $leader): void
    {
        $this->leader = $leader;
    }

    /**
     * @return MemberGroup
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param MemberGroup $group
     */
    public function setGroup(MemberGroup $group): void
    {
        $this->group = $group;
    }


}