<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Member Group Entity
 *
 * @ORM\Entity(repositoryClass="XRayLP\LearningCenterBundle\Repository\MemberGroupRepository")
 * @ORM\Table(name="tl_member_group")
 * @package XRayLP\LearningCenterBundle\Entity
 */
class MemberGroup
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", length=10)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    protected $tstamp = 0;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":""})
     */
    protected $redirect = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    protected $jumpTo = 0;

    /**
     * @ORM\Column(type="string", length=1, options={"default":""})
     */
    protected $disable = '';

    /**
     * @ORM\Column(type="string", length=10, options={"default":""})
     */
    protected $start = '';

    /**
     * @ORM\Column(type="string", length=10, options={"default":""})
     */
    protected $stop = '';

    /**
     * @ORM\Column(type="integer", length=11, options={"default":"1"})
     */
    protected $groupType = 1;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $courseNumber = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $courseName = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $classNumber = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $projectDescription = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $projectName = '';

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
    public function setId($id)
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
    public function setTstamp($tstamp)
    {
        $this->tstamp = $tstamp;
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
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param mixed $redirect
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return mixed
     */
    public function getJumpTo()
    {
        return $this->jumpTo;
    }

    /**
     * @param mixed $jumpTo
     */
    public function setJumpTo($jumpTo)
    {
        $this->jumpTo = $jumpTo;
    }

    /**
     * @return mixed
     */
    public function getDisable()
    {
        return $this->disable;
    }

    /**
     * @param mixed $disable
     */
    public function setDisable($disable)
    {
        $this->disable = $disable;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * @param mixed $stop
     */
    public function setStop($stop)
    {
        $this->stop = $stop;
    }

    /**
     * @return mixed
     */
    public function getGroupType()
    {
        return $this->groupType;
    }

    /**
     * @param mixed $groupType
     */
    public function setGroupType($groupType)
    {
        $this->groupType = $groupType;
    }

    /**
     * @return mixed
     */
    public function getCourseNumber()
    {
        return $this->courseNumber;
    }

    /**
     * @param mixed $courseNumber
     */
    public function setCourseNumber($courseNumber)
    {
        $this->courseNumber = $courseNumber;
    }

    /**
     * @return mixed
     */
    public function getCourseName()
    {
        return $this->courseName;
    }

    /**
     * @param mixed $courseName
     */
    public function setCourseName($courseName)
    {
        $this->courseName = $courseName;
    }

    /**
     * @return mixed
     */
    public function getClassNumber()
    {
        return $this->classNumber;
    }

    /**
     * @param mixed $classNumber
     */
    public function setClassNumber($classNumber)
    {
        $this->classNumber = $classNumber;
    }

    /**
     * @return mixed
     */
    public function getProjectDescription()
    {
        return $this->projectDescription;
    }

    /**
     * @param mixed $projectDescription
     */
    public function setProjectDescription($projectDescription)
    {
        $this->projectDescription = $projectDescription;
    }

    /**
     * @return mixed
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @param mixed $projectName
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    }

    /**
     *
     */
    public function getMembers(){
        $objMembers = \System::getContainer()->get('doctrine')->getRepository(Member::class)->findAll();
        foreach($objMembers as $key => $objMember)
        {
            if (!in_array($this->getId(), \StringUtil::deserialize($objMember->getGroups()))) {
                unset($objMembers[$key]);
            }
        }
        return $objMembers;
    }
}