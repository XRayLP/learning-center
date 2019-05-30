<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RepresentationPeriod
 * @package App\XRayLP\LearningCenterBundle\Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\StandardRepository")
 * @ORM\Entity()
 * @ORM\Table(name="tl_representation_period", options={"engine":"InnoDB"})
 */
class RepresentationPeriod
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
     * @ORM\ManyToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Period")
     */
    private $period;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $isRepPeriod;

    /**
     * @ORM\ManyToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Subject")
     */
    private $repSubject;

    /**
     * @ORM\ManyToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Member")
     */
    private $repTeacher;

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
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period): void
    {
        $this->period = $period;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getisRepPeriod()
    {
        return $this->isRepPeriod;
    }

    /**
     * @param mixed $isRepPeriod
     */
    public function setIsRepPeriod($isRepPeriod): void
    {
        $this->isRepPeriod = $isRepPeriod;
    }

    /**
     * @return mixed
     */
    public function getRepSubject()
    {
        return $this->repSubject;
    }

    /**
     * @param mixed $repSubject
     */
    public function setRepSubject($repSubject): void
    {
        $this->repSubject = $repSubject;
    }

    /**
     * @return mixed
     */
    public function getRepTeacher()
    {
        return $this->repTeacher;
    }

    /**
     * @param mixed $repTeacher
     */
    public function setRepTeacher($repTeacher): void
    {
        $this->repTeacher = $repTeacher;
    }


}