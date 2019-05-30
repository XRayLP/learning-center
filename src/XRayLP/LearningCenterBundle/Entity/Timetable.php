<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Timetable
 * @package App\XRayLP\LearningCenterBundle\Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\StandardRepository")
 * @ORM\Table(name="tl_timetable", options={"engine":"InnoDB"})
 */
class Timetable
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
     * @ORM\Column(type="string", options={"default":""})
     */
    private $schoolDays;

    /**
     * @ORM\Column(type="string", options={"default":""})
     */
    private $lessons;

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
     * @return mixed
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * @param mixed $lessons
     */
    public function setLessons($lessons): void
    {
        $this->lessons = $lessons;
    }


}