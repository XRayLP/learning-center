<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 12.01.19
 * Time: 17:17
 */

namespace App\XRayLP\LearningCenterBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class GradeEvent extends Event
{

    private $grade;

    /**
     * ProjectEvent constructor.
     * @param Project $project
     */
    public function __construct($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return Grade
     */
    public function getGrade()
    {
        return $this->grade;
    }
}
