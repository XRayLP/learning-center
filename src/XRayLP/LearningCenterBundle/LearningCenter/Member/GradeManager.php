<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use App\XRayLP\LearningCenterBundle\Entity\Grade;
use Symfony\Component\Translation\TranslatorInterface;

class GradeManager extends MemberGroupManager
{
    /**
     * @var Grade $grade
     */
    protected $grade;

    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator);
    }

    public function setGrade(Grade $grade)
    {
        $this->grade = $grade;
    }

    public function getGradename()
    {
        $grade_trans = $this->translator->trans('grade',[],'grade').' ';

        $grade = $this->grade;
        $gradeLevel = $grade->getGradeLevel()->getGradeNumber();
        $gradeSuffix = $grade->getSuffix();

        if (is_numeric($gradeSuffix)){
            $gradeName = $grade_trans.$gradeLevel.'-'.$gradeSuffix;
        } elseif (ctype_alpha($gradeSuffix)) {
            $gradeName = $grade_trans.$gradeLevel.$gradeSuffix;
        } else {
            $gradeName = $grade_trans.$gradeLevel.'_'.$gradeSuffix;
        }
        return $gradeName;
    }
}