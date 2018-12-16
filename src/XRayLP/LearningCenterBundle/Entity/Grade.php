<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use App\XRayLP\LearningCenterBundle\LearningCenter\Member\GradeManager;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Grade
 * @package App\XRayLP\LearningCenterBundle\Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\StandardRepository")
 * @ORM\Table(name="tl_grade", options={"engine":"InnoDB"})
 */
class Grade
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\GradeLevel")
     * @ORM\JoinColumn(name="pid", referencedColumnName="id")
     */
    private $gradeLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $tstamp;

    /**
     * @ORM\Column(type="string", options={"default":"0"})
     */
    private $suffix;

    /**
     * @ORM\ManyToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Member")
     */
    private $tutor;

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
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return GradeLevel
     */
    public function getGradeLevel()
    {
        return $this->gradeLevel;
    }

    /**
     * @param mixed $gradeLevel
     */
    public function setGradeLevel($gradeLevel): void
    {
        $this->gradeLevel = $gradeLevel;
    }

    /**
     * @return mixed
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param mixed $suffix
     */
    public function setSuffix($suffix): void
    {
        $this->suffix = $suffix;
    }

    /**
     * @return Member
     */
    public function getTutor()
    {
        return $this->tutor;
    }

    /**
     * @param mixed $tutor
     */
    public function setTutor($tutor): void
    {
        $this->tutor = $tutor;
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
    public function setGroup($group): void
    {
        $this->group = $group;
    }
}