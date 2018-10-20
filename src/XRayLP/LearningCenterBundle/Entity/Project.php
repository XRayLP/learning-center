<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use StringUtil;

/**
 * Project Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\ProjectRepository")
 * @ORM\Table(name="tl_projects", options={"engine":"InnoDB"})
 * @package App\XRayLP\LearningCenterBundle\Entity
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
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $public;

    /**
     * @ORM\OneToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Thread")
     */
    protected $thread;


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
     * @return MemberGroup
     */
    public function getGroupId(): MemberGroup
    {
        return \System::getContainer()->get('doctrine')->getRepository(MemberGroup::class)->findOneBy(['id' => $this->groupId]);
    }

    public function setGroupId(MemberGroup $memberGroup)
    {
        $this->groupId = $memberGroup->getId();
    }

    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return mixed
     */
    public function getLeader(): Member
    {
        return \System::getContainer()->get('doctrine')->getRepository(Member::class)->findOneBy(['id' => $this->leader]);;
    }

    /**
     * @param Member $member
     */
    public function setLeader(Member $member)
    {
        $this->leader = $member->getId();
    }

    public function addAdmin(Member $member)
    {
        $arrAdmins = array();
        $collection = $this->getAdmins();
        $collection->add($member);
        $arrCollection = $collection->toArray();
        foreach ($arrCollection as $admin)
        {
            if ($admin instanceof Member)
            {
                $arrAdmins[] = $admin->getId();
            }
        }

        $this->admins = serialize($arrAdmins);
    }

    public function removeAdmin(Member $member)
    {
        $arrAdmins = array();
        $collection = $this->getAdmins();
        $collection->removeElement($member);
        $arrCollection = $collection->toArray();
        foreach ($arrCollection as $admin)
        {
            if ($admin instanceof Member)
            {
                $arrAdmins[] = $admin->getId();
            }
        }

        $this->admins = serialize($arrAdmins);
    }

    /**
     * @return mixed
     */
    public function getAdmins(): ArrayCollection
    {
        $arrGroups = StringUtil::deserialize($this->admins);
        $groups = \System::getContainer()->get('doctrine')->getRepository(Member::class)->findBy(['id' => $arrGroups]);
        $collection = new ArrayCollection($groups);

        return $collection;
    }

    /**
     * @param mixed $admins
     */
    public function setAdmins($admins)
    {
        $this->admins = $admins;
    }

    /**
     * @return bool
     */
    public function getPublic(): bool
    {
        return $this->public;
    }

    /**
     * @param bool $public
     */
    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    /**
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param Thread $thread
     */
    public function setThread(Thread $thread): void
    {
        $this->thread = $thread;
    }



    /**
     * @return Event[]
     */
    public function getEvents()
    {
        $events = \System::getContainer()->get('doctrine')->getRepository(Event::class)->findBy(['pid' => $this->getCalendar()->getId()]);
        return $events;
    }

    /**
     * @return Calendar|Member
     */
    public function getCalendar()
    {
        return \System::getContainer()->get('doctrine')->getRepository(Calendar::class)->findOneByGroup($this->getGroupId());
    }
}