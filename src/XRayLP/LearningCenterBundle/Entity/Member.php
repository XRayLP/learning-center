<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use App\XRayLP\LearningCenterBundle\LearningCenter\Member\Avatar;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\MemberManagement;
use App\XRayLP\LearningCenterBundle\Request\UpdateMemberRequest;
use Contao\BackendPassword;
use Contao\Encryption;
use Contao\Password;
use Contao\StringUtil;
use Contao\System;
use DateTime;
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\ORM\Mapping as ORM;

/**
 * Member Entity
 *
 * @ORM\Entity(repositoryClass="App\XRayLP\LearningCenterBundle\Repository\MemberRepository")
 * @ORM\Table(name="tl_member")
 * @package App\XRayLP\LearningCenterBundle\Entity
 */
class Member implements ParticipantInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;


    /**
     * @ORM\Column(type="integer", options={"default":"0"})
     */
    protected $tstamp;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", length=11, options={"default":""})
     */
    protected $dateOfBirth;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $gender;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $company;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $street;

    /**
     * @ORM\Column(type="string", length=32, options={"default":""})
     */
    protected $postal;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=64, options={"default":""})
     */
    protected $state;

    /**
     * @ORM\Column(type="string", length=2, options={"default":""})
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=64, options={"default":""})
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", length=64, options={"default":""})
     */
    protected $mobile;

    /**
     * @ORM\Column(type="string", length=64, options={"default":""})
     */
    protected $fax;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $website;

    /**
     * @ORM\Column(type="string", length=5, options={"default":""})
     */
    protected $language;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $groups;

    /**
     * @ORM\Column(type="string", length=1, options={"default":""})
     */
    protected $login;

    /**
     * @ORM\Column(type="string", length=64, options={"default":""})
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $password;

    /** c
     * @ORM\Column(type="string", length=1, options={"default":""})
     */
    protected $assignDir;

    /**
     * @ORM\Column(type="string", length=16, options={"default":""})
     */
    protected $homeDir;

    /** c
     * @ORM\Column(type="string", length=1, options={"default":""})
     */
    protected $disable;

    /**
     * @ORM\Column(type="string", length=10, options={"default":""})
     */
    protected $start;

    /**
     * @ORM\Column(type="string", length=10, options={"default":""})
     */
    protected $stop;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    protected $dateAdded;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    protected $lastLogin;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    protected $currentLogin;

    /**
     * @ORM\Column(type="smallint", length=5, options={"default":"3"})
     */
    protected $loginCount;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    protected $locked;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $session;

    /**
     * @ORM\Column(type="integer", length=10, options={"default":"0"})
     */
    protected $createdOn;

    /**
     * @ORM\Column(type="string", length=32, options={"default":""})
     */
    protected $activation;

    /** c
     * @ORM\Column(type="string", length=1, options={"default":"0"})
     */
    protected $avatar;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $cloudSpace;

    /**
     * @ORM\Column(type="string", length=255, options={"default":""})
     */
    protected $memberType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $permissions= '';

    /**
     * @ORM\ManyToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Grade")
     * @ORM\JoinColumn(name="grade", referencedColumnName="id")
     */
    protected $grade;

    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

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
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * @param mixed $postal
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
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
     * @return ArrayCollection $collection
     */
    public function getGroups(): ArrayCollection
    {
        $arrGroups = StringUtil::deserialize($this->groups);
        $groups = \System::getContainer()->get('doctrine')->getRepository(MemberGroup::class)->findBy(['id' => $arrGroups]);
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
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAssignDir()
    {
        return $this->assignDir;
    }

    /**
     * @param mixed $assignDir
     */
    public function setAssignDir($assignDir)
    {
        $this->assignDir = $assignDir;
    }

    /**
     * @return File
     */
    public function getHomeDir(): File
    {
        return System::getContainer()->get('doctrine')->getRepository(File::class)->findOneByUuid($this->homeDir);
    }

    /**
     * @param File $homeDir
     */
    public function setHomeDir(File $homeDir)
    {
        if ($homeDir->getType() == 'folder')
        {
            $this->homeDir = $homeDir->getUuid();
        }
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
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param mixed $dateAdded
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param mixed $lastLogin
     */
    public function setLastLogin(DateTime $lastLogin = NULL)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return mixed
     */
    public function getCurrentLogin()
    {
        return $this->currentLogin;
    }

    /**
     * @param mixed $currentLogin
     */
    public function setCurrentLogin($currentLogin)
    {
        $this->currentLogin = $currentLogin;
    }

    /**
     * @return mixed
     */
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * @param mixed $loginCount
     */
    public function setLoginCount($loginCount)
    {
        $this->loginCount = $loginCount;
    }

    /**
     * @return mixed
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @param mixed $locked
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param mixed $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return mixed
     */
    public function getActivation()
    {
        return $this->activation;
    }

    /**
     * @param mixed $activation
     */
    public function setActivation($activation)
    {
        $this->activation = $activation;
    }

    /**
     * @return Avatar
     */
    public function getAvatar()
    {
        return (new Avatar($this));
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getCloudSpace()
    {
        $arrSpace = StringUtil::deserialize($this->cloudSpace);

        //gets and build the max space for the current user
        switch($arrSpace['unit']){
            case 'B':
                $maxSpace = $arrSpace['value'];
                break;
            case 'KB':
                $maxSpace = $arrSpace['value']*1024;
                break;
            case 'MB':
                $maxSpace = $arrSpace['value']*1024*1024;
                break;
            case 'GB':
                $maxSpace = $arrSpace['value']*1024*1024*1024;
                break;
        }

        return $maxSpace;
    }

    /**
     * @param mixed $cloudSpace
     */
    public function setCloudSpace($cloudSpace)
    {
        $this->cloudSpace = $cloudSpace;
    }

    /**
     * @return mixed
     */
    public function getMemberType()
    {
        return $this->memberType;
    }

    /**
     * @param mixed $memberType
     */
    public function setMemberType($memberType)
    {
        $this->memberType = $memberType;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param mixed $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getProjects()
    {
        $entityManager = \System::getContainer()->get('doctrine')->getRepository(Project::class);

        return $entityManager->findBy(array('groupId' => StringUtil::deserialize($this->groups)));
    }

    public function isAdmin(): bool
    {
        return $this->memberType == 'ROLE_ADMIN';
    }

    public function isPlanner(): bool
    {
        return $this->memberType == 'ROLE_PLANNER';
    }

    public function isTeacher(): bool
    {
        return $this->memberType == 'ROLE_TEACHER';
    }

    public function isStudent(): bool
    {
        return $this->memberType == 'ROLE_STUDENT';
    }

    public function updateRequest(UpdateMemberRequest $memberRequest)
    {
        $this->setFirstname($memberRequest->getFirstname());
        $this->setLastname($memberRequest->getLastname());
        $this->setDateOfBirth($memberRequest->getDateOfBirth()->getTimestamp());
        $this->setGender($memberRequest->getGender());
        $this->setStreet($memberRequest->getStreet());
        $this->setPostal($memberRequest->getPostal());
        $this->setCity($memberRequest->getCity());
        $this->setState($memberRequest->getState());
        $this->setCountry($memberRequest->getCountry());
        $this->setPhone($memberRequest->getPhone());
        $this->setMobile($memberRequest->getMobile());
        $this->setFax($memberRequest->getFax());
        $this->setPassword(password_hash($memberRequest->getPassword(), PASSWORD_DEFAULT));

    }


}