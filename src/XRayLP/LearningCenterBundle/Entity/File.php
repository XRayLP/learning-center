<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Contao\StringUtil;
use Contao\System;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * File Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\FileRepository")
 * @ORM\Table(name="tl_files")
 * @package App\XRayLP\LearningCenterBundle\Entity
 */
class File
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", length=10)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $pid;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $tstamp = 0;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=16, options={"default":""})
     */
    private $type = '';

    /**
     * @ORM\Column(type="string", length=1022, options={"default":""})
     */
    private $path = '';

    /**
     * @ORM\Column(type="string", length=16, options={"default":""})
     */
    private $extension = '';

    /**
     * @ORM\Column(type="string", length=32, options={"default":""})
     */
    private $hash = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"1"})
     */
    private $found = 1;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $name = '';

    /**
     * @ORM\Column(type="integer", length=11, options={"default":"0"})
     */
    private $importantPartX = 0;

    /**
     * @ORM\Column(type="integer", length=11, options={"default":"0"})
     */
    private $importantPartY = 0;

    /**
     * @ORM\Column(type="integer", length=11, options={"default":"0"})
     */
    private $importantPartWidth = 0;

    /**
     * @ORM\Column(type="integer", length=11, options={"default":"0"})
     */
    private $importantPartHeight = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meta;

    /**
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $shared_tstamp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $shared_groups;

    /**
     * @ORM\Column(type="string", length=1, options={"default":""})
     */
    private $shared = '';

    /**
     * @ORM\Column(type="integer", length=11, nullable=true)
     */
    private $owner;

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
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param mixed $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    /**
     * @return mixed
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    public function getDate()
    {
        return date('d. M Y', $this->tstamp);
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
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getFound()
    {
        return $this->found;
    }

    /**
     * @param mixed $found
     */
    public function setFound($found)
    {
        $this->found = $found;
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
    public function getImportantPartX()
    {
        return $this->importantPartX;
    }

    /**
     * @param mixed $importantPartX
     */
    public function setImportantPartX($importantPartX)
    {
        $this->importantPartX = $importantPartX;
    }

    /**
     * @return mixed
     */
    public function getImportantPartY()
    {
        return $this->importantPartY;
    }

    /**
     * @param mixed $importantPartY
     */
    public function setImportantPartY($importantPartY)
    {
        $this->importantPartY = $importantPartY;
    }

    /**
     * @return mixed
     */
    public function getImportantPartWidth()
    {
        return $this->importantPartWidth;
    }

    /**
     * @param mixed $importantPartWidth
     */
    public function setImportantPartWidth($importantPartWidth)
    {
        $this->importantPartWidth = $importantPartWidth;
    }

    /**
     * @return mixed
     */
    public function getImportantPartHeight()
    {
        return $this->importantPartHeight;
    }

    /**
     * @param mixed $importantPartHeight
     */
    public function setImportantPartHeight($importantPartHeight)
    {
        $this->importantPartHeight = $importantPartHeight;
    }

    /**
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param mixed $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return mixed
     */
    public function getSharedTstamp()
    {
        return $this->shared_tstamp;
    }

    public function getSharedDate()
    {
        return date('d. M Y', $this->shared_tstamp);
    }

    /**
     * @param mixed $shared_tstamp
     */
    public function setSharedTstamp($shared_tstamp)
    {
        $this->shared_tstamp = $shared_tstamp;
    }

    public function removeSharedGroup(MemberGroup $memberGroup)
    {
        $arrGroups = array();
        $collection = $this->getSharedGroups();
        $collection->removeElement($memberGroup);
        $arrCollection = $collection->toArray();

        foreach ($arrCollection as $memberGroup)
        {
            if ($memberGroup instanceof MemberGroup)
            {
                $arrGroups[] = $memberGroup->getId();
            }
        }
        $this->shared_groups = serialize($arrGroups);

        if (empty($arrGroups))
        {
            $this->setShared(false);
            $this->setSharedTstamp(null);
        }
    }

    public function addSharedGroup(MemberGroup $memberGroup)
    {
        $arrGroups = array();
        $collection = $this->getSharedGroups();
        $collection->add($memberGroup);
        $arrCollection = $collection->toArray();

        foreach ($arrCollection as $memberGroup)
        {
            if ($memberGroup instanceof MemberGroup)
            {
                $arrGroups[] = $memberGroup->getId();
            }
        }

        $this->shared_groups = serialize($arrGroups);

        if (count($arrGroups) === 1){
            $this->setShared(true);
            $this->setSharedTstamp(time());
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getSharedGroups()
    {
        $arrGroups = StringUtil::deserialize($this->shared_groups);
        $groups = \System::getContainer()->get('doctrine')->getRepository(MemberGroup::class)->findBy(['id' => $arrGroups]);
        $collection = new ArrayCollection($groups);

        return $collection;
    }

    /**
     * @param mixed $groups
     */
    public function setSharedGroups($groups)
    {
        $this->shared_groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getShared()
    {
        return $this->shared;
    }

    /**
     * @param mixed $shared
     */
    public function setShared($shared)
    {
        $this->shared = $shared;
    }

    /**
     * @return Member
     */
    public function getOwner(): Member
    {
        return System::getContainer()->get('doctrine')->getRepository(Member::class)->findOneById($this->owner);
    }

    /**
     * @param Member $owner
     */
    public function setOwner(Member $owner)
    {
        $this->owner = $owner->getId();
    }


}