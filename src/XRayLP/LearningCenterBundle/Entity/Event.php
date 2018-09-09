<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Contao\System;
use \Doctrine\ORM\Mapping as ORM;

/**
 * Event Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\EventRepository")
 * @ORM\Table(name="tl_calendar_events")
 * @package App\XRayLP\LearningCenterBundle\Entity
 */
class Event
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
    private $pid;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $tstamp = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=128, options={"default":""})
     */
    private $alias = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $author = 0;

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $addTime = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $startTime = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $endTime = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $startDate = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $endDate = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $location = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $address = '';

    /**
     * @ORM\Column(type="text", options={"default":""})
     */
    private $teaser = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $addImage = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $overwriteMeta = '';

    /**
     * @ORM\Column(type="binary", length=16, options={"default":""})
     */
    private $singleSRC = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $alt = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $imageTitle = '';

    /**
     * @ORM\Column(type="string", length=64, options={"default":""})
     */
    private $size = '';

    /**
     * @ORM\Column(type="string", length=128, options={"default":""})
     */
    private $imagemargin = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $imageUrl = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $fullsize = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $caption = '';

    /**
     * @ORM\Column(type="string", length=32, options={"default":""})
     */
    private $floating = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $recurring = '';

    /**
     * @ORM\Column(type="string", length=64, options={"default":""})
     */
    private $repeatEach = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $repeatEnd = 0;

    /**
     * @ORM\Column(type="smallint", length=5, options={"default":"0"})
     */
    private $recurrences = 0;

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $addEnclosure = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $enclosure = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $orderEnclosure = '';

    /**
     * @ORM\Column(type="string", length=32, options={"default":""})
     */
    private $source = '';

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $jumpTo = 0;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    private $articleId = 0;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $url = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $target = '';

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    private $cssClass = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $noComments = '';

    /**
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    private $published = '';

    /**
     * @ORM\Column(type="string", length=10, options={"default":""})
     */
    private $start = '';

    /**
     * @ORM\Column(type="string", length=10, options={"default":""})
     */
    private $stop = '';

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
    public function getPid(): Calendar
    {
        return System::getContainer()->get('doctrine')->getRepository(Event::class)->findOneById($this->pid);
    }

    /**
     * @param Calendar $calendar
     */
    public function setPid(Calendar $calendar)
    {
        $this->pid = $calendar->getId();
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
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getAddTime()
    {
        return $this->addTime;
    }

    /**
     * @param mixed $addTime
     */
    public function setAddTime($addTime)
    {
        $this->addTime = $addTime;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * @param mixed $teaser
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
    }

    /**
     * @return mixed
     */
    public function getAddImage()
    {
        return $this->addImage;
    }

    /**
     * @param mixed $addImage
     */
    public function setAddImage($addImage)
    {
        $this->addImage = $addImage;
    }

    /**
     * @return mixed
     */
    public function getOverwriteMeta()
    {
        return $this->overwriteMeta;
    }

    /**
     * @param mixed $overwriteMeta
     */
    public function setOverwriteMeta($overwriteMeta)
    {
        $this->overwriteMeta = $overwriteMeta;
    }

    /**
     * @return mixed
     */
    public function getSingleSRC()
    {
        return $this->singleSRC;
    }

    /**
     * @param mixed $singleSRC
     */
    public function setSingleSRC($singleSRC)
    {
        $this->singleSRC = $singleSRC;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * @return mixed
     */
    public function getImageTitle()
    {
        return $this->imageTitle;
    }

    /**
     * @param mixed $imageTitle
     */
    public function setImageTitle($imageTitle)
    {
        $this->imageTitle = $imageTitle;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getImagemargin()
    {
        return $this->imagemargin;
    }

    /**
     * @param mixed $imagemargin
     */
    public function setImagemargin($imagemargin)
    {
        $this->imagemargin = $imagemargin;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return mixed
     */
    public function getFullsize()
    {
        return $this->fullsize;
    }

    /**
     * @param mixed $fullsize
     */
    public function setFullsize($fullsize)
    {
        $this->fullsize = $fullsize;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return mixed
     */
    public function getFloating()
    {
        return $this->floating;
    }

    /**
     * @param mixed $floating
     */
    public function setFloating($floating)
    {
        $this->floating = $floating;
    }

    /**
     * @return mixed
     */
    public function getRecurring()
    {
        return $this->recurring;
    }

    /**
     * @param mixed $recurring
     */
    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
    }

    /**
     * @return mixed
     */
    public function getRepeatEach()
    {
        return $this->repeatEach;
    }

    /**
     * @param mixed $repeatEach
     */
    public function setRepeatEach($repeatEach)
    {
        $this->repeatEach = $repeatEach;
    }

    /**
     * @return mixed
     */
    public function getRepeatEnd()
    {
        return $this->repeatEnd;
    }

    /**
     * @param mixed $repeatEnd
     */
    public function setRepeatEnd($repeatEnd)
    {
        $this->repeatEnd = $repeatEnd;
    }

    /**
     * @return mixed
     */
    public function getRecurrences()
    {
        return $this->recurrences;
    }

    /**
     * @param mixed $recurrences
     */
    public function setRecurrences($recurrences)
    {
        $this->recurrences = $recurrences;
    }

    /**
     * @return mixed
     */
    public function getAddEnclosure()
    {
        return $this->addEnclosure;
    }

    /**
     * @param mixed $addEnclosure
     */
    public function setAddEnclosure($addEnclosure)
    {
        $this->addEnclosure = $addEnclosure;
    }

    /**
     * @return mixed
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param mixed $enclosure
     */
    public function setEnclosure($enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @return mixed
     */
    public function getOrderEnclosure()
    {
        return $this->orderEnclosure;
    }

    /**
     * @param mixed $orderEnclosure
     */
    public function setOrderEnclosure($orderEnclosure)
    {
        $this->orderEnclosure = $orderEnclosure;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
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
    public function getArticleId()
    {
        return $this->articleId;
    }

    /**
     * @param mixed $articleId
     */
    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param mixed $cssClass
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * @return mixed
     */
    public function getNoComments()
    {
        return $this->noComments;
    }

    /**
     * @param mixed $noComments
     */
    public function setNoComments($noComments)
    {
        $this->noComments = $noComments;
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
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



}