<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;

use Contao\DataContainer;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\StringUtil;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use XRayLP\LearningCenterBundle\Entity\Project;
use XRayLP\LearningCenterBundle\Model\ProjectModel;

/**
 * Class Project
 *
 * @property \Contao\User|static User
 */
class ProjectService
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ProjectService constructor.
     * @param EntityManager $entityManager
     * @param UrlGeneratorInterface $router
     */
    public function __construct(UrlGeneratorInterface $router, EntityManager $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    /**
     * Generates a Project Alias
     *
     * @param $varValue
     * @param DataContainer $dc
     * @return string
     * @throws \Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        if ($varValue === '')
        {
            $autoAlias = true;
            $varValue = \StringUtil::generateAlias($dc->activeRecord->name);
        }

        $projectList = ProjectModel::findByAlias($varValue);

        // Check whether the news alias exists
        if (count($projectList) > 1 && $autoAlias === false)
        {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        // Add ID to alias
        if (count($projectList) > 0 && $autoAlias === true)
        {
            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }

    /**
     * Returns an array of projects, of which a user is part of
     *
     * @param \FrontendUser|MemberModel $objMember
     * @return array
     */
    public function getProjectsOfUser($objMember)
    {
        //Projects
        $objProjects = $this->entityManager->getRepository(Project::class)->findAll();

        foreach ($objProjects as $objProject)
        {
            if (in_array($objProject->getGroupId(), StringUtil::deserialize($objMember->groups)))
            {
                $url = $this->router->generate('learningcenter_projects.details', array('alias' => $objProject->getAlias()));

                $projects[] = array(
                    'id'            => $objProject->getId(),
                    'name'          => $objProject->getName(),
                    'description'   => $objProject->getDescription(),
                    'url'           => $url
                );
            }

        }
        return $projects;
    }

    /**
     * Returns an array of all users of an group
     *
     * @param $id
     * @return array
     */
    public function getAllUsersOfGroup($id)
    {
        $objMembers = MemberModel::findBy(array("groups LIKE ?"), array('%"'.$id.'"%'));
        foreach ($objMembers  as $objMember)
        {
            $members[] = array(
                'firstname' => $objMember->firstname,
                'lastname'  => $objMember->lastname,
                'avatar'    => "/bundles/learningcenter/avatar/$objMember->id.png",
                'url'       => $this->router->generate('learningcenter_user.details', array('username' => $objMember->username))
            );
        }
        return $members;
    }

    /**
     * Creates an array with information about a project
     *
     * @param $alias
     * @return array
     */
    public function getProjectDetails($alias)
    {
        $objProject = $this->entityManager->getRepository('LearningCenterBundle:Project')->findOneBy(array('alias' => $alias));

        //get the users
        if (null !== $objProject->getGroupId()) {
            $members = $this->getAllUsersOfGroup($objProject->getGroupId());
        }

        //array
        return $project = array(
            'id'            => $objProject->getId(),
            'name'          => $objProject->getAlias(),
            'description'   => $objProject->getDescription(),
            'members'       => $members
        );
    }
}