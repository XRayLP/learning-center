<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Contao\StringUtil;
use Contao\System;
use Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\ORM\Mapping as ORM;

/**
 * Calendar Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\CalendarRepository")
 * @ORM\Table(name="tl_calendar")
 * @package App\XRayLP\LearningCenterBundle\Entity
 */
class Calendar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $tstamp;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $title;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $jumpTo = 0;

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $protected = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $groups = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $allowComments = '';

    /**
     * @ORM\Column(type="string", length=32, options={"default":""})
     */
    private $notify = '';

    /**
     * @ORM\Column(type="string", length=32, options={"default":""})
     */
    private $sortOrder = '';

    /**
     * @ORM\Column(type="smallint", length=5, options={"default":""})
     */
    private $perPage = 0;

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $moderate = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $bbcode = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $requireLogin = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $disableCaptcha = '';


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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getProtected()
    {
        return $this->protected;
    }

    /**
     * @param mixed $protected
     */
    public function setProtected($protected)
    {
        $this->protected = $protected;
    }

    public function removeGroup(MemberGroup $memberGroup)
    {
        $arrGroups = array();
        $collection = $this->getGroups();
        $collection->removeElement($memberGroup);
        $arrCollection = $collection->toArray();

        foreach ($arrCollection as $memberGroup)
        {
            if ($memberGroup instanceof MemberGroup)
            {
                $arrGroups[] = $memberGroup->getId();
            }
        }
        $this->groups = serialize($arrGroups);
    }

    public function addGroup(MemberGroup $memberGroup)
    {
        $arrGroups = array();
        $collection = $this->getGroups();
        $collection->add($memberGroup);
        $arrCollection = $collection->toArray();

        foreach ($arrCollection as $memberGroup)
        {
            if ($memberGroup instanceof MemberGroup)
            {
                $arrGroups[] = $memberGroup->getId();
            }
        }

        $this->groups = serialize($arrGroups);
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups()
    {
        $arrGroups = StringUtil::deserialize($this->groups);
        $groups = System::getContainer()->get('doctrine')->getRepository(MemberGroup::class)->findBy(['id' => $arrGroups]);
        $collection = new ArrayCollection($groups);

        return $collection;
    }

    /**
     * @param mixed $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getAllowComments()
    {
        return $this->allowComments;
    }

    /**
     * @param mixed $allowComments
     */
    public function setAllowComments($allowComments)
    {
        $this->allowComments = $allowComments;
    }

    /**
     * @return mixed
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * @param mixed $notify
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @param mixed $sortOrder
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return mixed
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param mixed $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * @return mixed
     */
    public function getModerate()
    {
        return $this->moderate;
    }

    /**
     * @param mixed $moderate
     */
    public function setModerate($moderate)
    {
        $this->moderate = $moderate;
    }

    /**
     * @return mixed
     */
    public function getBbcode()
    {
        return $this->bbcode;
    }

    /**
     * @param mixed $bbcode
     */
    public function setBbcode($bbcode)
    {
        $this->bbcode = $bbcode;
    }

    /**
     * @return mixed
     */
    public function getRequireLogin()
    {
        return $this->requireLogin;
    }

    /**
     * @param mixed $requireLogin
     */
    public function setRequireLogin($requireLogin)
    {
        $this->requireLogin = $requireLogin;
    }

    /**
     * @return mixed
     */
    public function getDisableCaptcha()
    {
        return $this->disableCaptcha;
    }

    /**
     * @param mixed $disableCaptcha
     */
    public function setDisableCaptcha($disableCaptcha)
    {
        $this->disableCaptcha = $disableCaptcha;
    }
}