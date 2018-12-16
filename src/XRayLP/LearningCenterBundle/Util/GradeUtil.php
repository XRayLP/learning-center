<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Util;


use App\XRayLP\LearningCenterBundle\Entity\Grade;
use App\XRayLP\LearningCenterBundle\Entity\GradeLevel;
use App\XRayLP\LearningCenterBundle\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;

class GradeUtil
{
    private $gradeLevelRepository;
    private $subjectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->gradeLevelRepository = $entityManager->getRepository(GradeLevel::class);
        $this->subjectRepository = $entityManager->getRepository(Subject::class);
    }

    /**
     * Gets all registered Grade Levels and returns them in an Array[$id] = 'grade. Klasse'
     *
     * @return array
     */
    public function getAllGradeLevels()
    {
        $gradeLevels = $this->gradeLevelRepository->findAll();

        $arrLevels = array();

        foreach ($gradeLevels as $level)
        {
            $arrLevels[$level->getId()] = $level->getGradeNumber().'. Klasse';
        }

        return $arrLevels;
    }

    /**
     * Gets all registered Subjects and returns them in an Array[$id] = 'subjectName'
     *
     * @return array
     */
    public function getAllSubjects()
    {
        $subjects = $this->subjectRepository->findAll();

        $arrSubjects = array();

        foreach ($subjects as $subject)
        {
            $arrSubjects[$subject->getId()] = $subject->getName();
        }

        return $arrSubjects;
    }

    /**
     * label_callback: Ermöglicht individuelle Bezeichnungen in der Listenansicht.
     * @param $row
     * @param $label
     * @return string
     */
    public function getGradeLabel($row, $label){

        $gradeLevel = $this->gradeLevelRepository->findOneById($row["pid"]);

        $newLabel = $gradeLevel->getGradeNumber().$row["suffix"];
        return $newLabel;
    }

    /**
     * label_callback: Ermöglicht individuelle Bezeichnungen in der Listenansicht.
     * @param $row
     * @param $label
     * @return string
     */
    public function getCourseLabel($row, $label){

        $gradeLevel = $this->gradeLevelRepository->findOneById($row["gradeLevel_id"]);
        $subject = $this->subjectRepository->findOneById($row["subject_id"]);

        $newLabel = 'Kurs '.$subject->getName().' '.$gradeLevel->getGradeNumber().$row["suffix"];
        return $newLabel;
    }
}