<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Project Entity
 *
 * @ORM\Entity(repositoryClass="XRayLP\LearningCenterBundle\Repository\ProjectRepository")
 * @ORM\Table(name="tl_projects")
 * @package XRayLP\LearningCenterBundle\Entity
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"}, nullable=true)
     */
    protected $tstamp;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $name;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    protected $leader;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $admins;

    /**
     * @ORM\Column(type="text", options={"default":""})
     */
    protected $description;

    /**
     * @ORM\Column(type="integer")
     */
    protected $groupId;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $confirmed;


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

    public function getConfirmed()
    {
        return $this->confirmed;
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
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param int $group
     */
    public function setGroupId($group): void
    {
        $this->groupId = $group;
    }

    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return mixed
     */
    public function getLeader()
    {
        return $this->leader;
    }

    /**
     * @param mixed $leader
     */
    public function setLeader($leader)
    {
        $this->leader = $leader;
    }

    /**
     * @return mixed
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    /**
     * @param mixed $admins
     */
    public function setAdmins($admins)
    {
        $this->admins = $admins;
    }

    public function getGroup(): MemberGroup{
        return \System::getContainer()->get('doctrine')->getRepository(MemberGroup::class)->findOneBy(array('id' => $this->getGroupId()));
    }
}