<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * Class Notification
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\MemberRepository")
 * @ORM\Table(name="tl_notifications", options={"engine":"InnoDB"})
 * @package App\XRayLP\LearningCenterBundle\Entity
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", length=255, nullable=true)
     */
    protected $tstamp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $message;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $variables;

    /**
     * @ORM\Column(type="integer")
     */
    protected $member;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $seen = 0;

    /**
     * @ORM\Column(type="string", length=255, options={"default":"envelope"})
     */
    protected $icon = 'envelope';

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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getVariables()
    {
        return \StringUtil::deserialize($this->variables);
    }

    public function setVariables($variables)
    {
        $this->variables = serialize($variables);
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return \System::getContainer()->get('doctrine')->getRepository(Member::class)->findOneById($this->member);
    }

    /**
     * @param Member $member
     */
    public function setMember(Member $member)
    {
        $this->member = $member->getId();
    }

    /**
     * @return mixed
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * @param mixed $seen
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }


}