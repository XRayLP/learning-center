<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GradeLevel
 * @package App\XRayLP\LearningCenterBundle\Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\StandardRepository")
 * @ORM\Entity()
 * @ORM\Table(name="tl_grade_level", options={"engine":"InnoDB"})
 */
class GradeLevel
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
     * @ORM\Column(type="integer", options={"default":"0"})
     */
    private $gradeNumber;

    /**
     * @ORM\Column(type="string", options={"default":"string"})
     */
    private $gradeSuffix;

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
     * @return mixed
     */
    public function getGradeNumber()
    {
        return $this->gradeNumber;
    }

    /**
     * @param mixed $gradeNumber
     */
    public function setGradeNumber($gradeNumber): void
    {
        $this->gradeNumber = $gradeNumber;
    }

    /**
     * @return mixed
     */
    public function getGradeSuffix()
    {
        return $this->gradeSuffix;
    }

    /**
     * @param mixed $gradeSuffix
     */
    public function setGradeSuffix($gradeSuffix): void
    {
        $this->gradeSuffix = $gradeSuffix;
    }


}