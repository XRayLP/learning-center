<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Controller;


use Contao\FrontendUser;
use Contao\System;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use XRayLP\LearningCenterBundle\Entity\Member;
use XRayLP\LearningCenterBundle\Entity\MemberGroup;
use XRayLP\LearningCenterBundle\Entity\Project;
use XRayLP\LearningCenterBundle\Event\Events;
use XRayLP\LearningCenterBundle\Event\ProjectEvent;
use XRayLP\LearningCenterBundle\Form\ChooseUserType;
use XRayLP\LearningCenterBundle\Form\ConfirmProjectType;
use XRayLP\LearningCenterBundle\Form\CreateProjectType;
use XRayLP\LearningCenterBundle\Form\UpdateProjectType;
use XRayLP\LearningCenterBundle\Member\MemberGroupManagement;
use XRayLP\LearningCenterBundle\Member\MemberManagement;
use XRayLP\LearningCenterBundle\Project\ProjectMemberManagement;
use XRayLP\LearningCenterBundle\Request\CreateProjectRequest;
use XRayLP\LearningCenterBundle\Request\UpdateProjectRequest;
use XRayLP\LearningCenterBundle\Request\UpdateUserGroupRequest;

class ProjectController extends AbstractController
{
    protected $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * A Project List
     *
     * @return RedirectResponse|Response
     */
    public function mainAction()
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $errors = array();
            $member = System::getContainer()->get('doctrine')->getRepository(Member::class)->findOneBy(array('id' => FrontendUser::getInstance()->id));
            $objProjects = $member->getProjects();
            $projects = array();

            if ($objProjects !== null) {
                foreach ($objProjects as $objProject) {
                    $url = System::getContainer()->get('router')->generate('learningcenter_projects.details', array('alias' => $objProject->getId()));

                    $projects[] = array(
                        'id' => $objProject->getId(),
                        'name' => $objProject->getName(),
                        'description' => $objProject->getDescription(),
                        'url' => $url,
                        'confirmed' => $objProject->getConfirmed()
                    );
                }
            } else {
                array_push($errors, "You haven't got any projects!");
            }

            //Twig
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/project_list.html.twig',
                [
                    'projects'  => $projects,
                    'errors'    => $errors
                ]
            );
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter_login');
        }

    }

    /**
     * Project Details
     *
     * @param $alias
     * @return RedirectResponse|Response
     */
    public function detailAction($alias)
    {

        $objProject = $this->getDoctrine()->getRepository(Project::class)->findOneBy(array('id' => $alias));
        $this->denyAccessUnlessGranted('view', $objProject);

        //all project variables
        $project = array(
            'creation' => $objProject->getTstamp(),
            'name' => $objProject->getName(),
            'description' => $objProject->getDescription(),
        );

        $twigRenderer = \System::getContainer()->get('templating');
        $rendered = $twigRenderer->render('@LearningCenter/modules/project/project_details.html.twig',
            [
                'project' => $project,
            ]
        );

        return new Response($rendered);
    }

    /**
     * Project Members
     *
     * @param $alias
     * @return RedirectResponse|Response
     */
    public function membersAction($alias)
    {

        $objProject = $this->getDoctrine()->getRepository(Project::class)->findOneBy(array('id' => $alias));
        $this->denyAccessUnlessGranted('view', $objProject);

        $projectMemberManagement = new ProjectMemberManagement($objProject);
        $objMembers = $objProject->getGroup()->getMembers();

        //member table
        foreach ($objMembers as $objMember) {
            $memberManagement = new MemberManagement($objMember);
            $members[] = array(
                'firstname' => $objMember->getFirstname(),
                'lastname' => $objMember->getLastname(),
                'url' => '',
                'avatar' => $memberManagement->getAvatar(),
                'id'    => $objMember->getId(),
                'isLeader' => $projectMemberManagement->isLeader($objMember),
                'isAdmin'   => $projectMemberManagement->isAdmin($objMember),
            );
        }

        //sort
        $aIndex = 1;
        $lIndex = 0;
        //sort by type (admin/leader/member)
        for ($i=0; $i < count($members); $i++) {
            if ($members[$i]['isLeader'] == true) {
                $tmp = $members[$lIndex];
                $members[$lIndex] = $members[$i];
                $members[$i] = $tmp;
            } elseif ($members[$i]['isAdmin'] == true) {
                $tmp = $members[$aIndex];
                $members[$aIndex] = $members[$i];
                $members[$i] = $tmp;
                $aIndex++;
            }
        }

        //sort the admins
        for ($i=$lIndex+1; $i < $aIndex; $i++) {
            $min = $i;
            for($a=$i; $a < count($members); $a++) {
                if ($members[$a]['lastname'] < $members[$min]['lastname']) {
                    $min = $a;
                }
            }
            $tmp = $members[$i];
            $members[$i] = $members[$min];
            $members[$min] = $tmp;
        }

        //sort the members
        for ($i=$aIndex; $i < count($members); $i++) {
            $min = $i;
            for($a=$i; $a < count($members); $a++) {
                if ($members[$a]['lastname'] < $members[$min]['lastname']) {
                    $min = $a;
                }
            }
            $tmp = $members[$i];
            $members[$i] = $members[$min];
            $members[$min] = $tmp;
        }

        //all project variables
        $project = array(
            'id' => $objProject->getId(),
            'group' => $objProject->getGroupId(),
            'name' => $objProject->getName(),
            'members' => $members
        );

        $twigRenderer = \System::getContainer()->get('templating');
        $rendered = $twigRenderer->render('@LearningCenter/modules/project/project_members.html.twig',
            [
                'project' => $project,
            ]
        );

        return new Response($rendered);
    }


    public function updateAction(int $alias, Request $request)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->findOneBy(array('id' => $alias));
        $updateProjectRequest = UpdateProjectRequest::fromProject($project);
        $twigRenderer = \System::getContainer()->get('templating');

        $form = $this->createForm(UpdateProjectType::class, $updateProjectRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $project->setName($updateProjectRequest->getName());
            $project->setDescription($updateProjectRequest->getDescription());
            $project->setLeader($updateProjectRequest->getLeader()->getId());

            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('learningcenter_projects.details', array('alias' => $alias));
        }

        $rendered = $twigRenderer->render('@LearningCenter/modules/project_create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
        return new Response($rendered);
    }


    public function createAction(Request $request)
    {

        $project = new Project();
        $this->denyAccessUnlessGranted('create', $project);
        $twigRenderer = \System::getContainer()->get('templating');

        $createProjectRequest = new CreateProjectRequest();

        //Form Creation
        $form = $this->createForm(CreateProjectType::class, $createProjectRequest);
        if ($this->isGranted('lead', $project)){
            $form->remove('leader');
        }
        $form->handleRequest($request);

        //Form Handle after Submit
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();

            $project = new Project();
            $project->setName($createProjectRequest->getName());
            $project->setDescription($createProjectRequest->getDescription());
            if ($this->isGranted('lead', $project)){
                $project->setLeader($this->getUser()->id);
                $project->setConfirmed(1);
            } else {
                $project->setLeader($createProjectRequest->getLeader()->getId());
                $project->setConfirmed(0);
            }
            $project->setAdmins('');

            //creates new group for the project
            $group = new MemberGroup();
            $group->setTstamp(time());
            $group->setName($project->getName());
            $group->setGroupType(4);


            //save group in db
            $entityManager->persist($group);
            $entityManager->flush();

            $project->setGroupId($group->getId());

            $entityManager->persist($project);
            $entityManager->flush();


            $memberProjectManagement = new ProjectMemberManagement($project);
            $memberProjectManagement->add($this->getDoctrine()->getRepository(Member::class)->findOneBy(array('id' => $this->getUser()->id)));
            if (!$this->isGranted('lead', $project)) {
                $memberProjectManagement->add($this->getDoctrine()->getRepository(Member::class)->findOneBy(array('id' => $createProjectRequest->getLeader()->getId())));
            }

            $this->eventDispatcher->dispatch(Events::PROJECT_CREATE_SUCCESS_EVENT, new ProjectEvent($project));

            return $this->redirectToRoute('learningcenter_projects');
        }
        $rendered = $twigRenderer->render('@LearningCenter/modules/project_create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );

        return new Response($rendered);
    }

    public function confirmAction(Request $request, int $alias)
    {
        $render = array();
        $data = array();
        $translator = $this->get('translator');
        $entityManager = $this->getDoctrine()->getManager();

        $project = $this->getDoctrine()->getRepository(Project::class)->findOneById($alias);
        $render['project'] = $project;

        if ($request->get('confirm') === "1") {
            $project->setConfirmed(1);
            $entityManager->persist($project);
            $entityManager->flush();
        } elseif ($request->get('confirm') === "0") {
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('learningcenter_projects');
        }

        if ($project->getConfirmed()) {
            $message = $translator->trans('project.confirmed', array('%name%' => $project->getName()));
            $render['confirmed'] = true;
        } else {
            $message = $translator->trans('project.need.confirm', array('%name%' => $project->getName()));
            $render['confirmed'] = false;
            if ($this->isGranted('lead', $project)) {
                $form = $this->createFormBuilder($data)->getForm();
                $form->handleRequest($request);
                $render['form'] = $form->createView();
            }
        }
        $render['message'] = $message;

        $twigRenderer = \System::getContainer()->get('templating');
        $rendered = $twigRenderer->render('@LearningCenter/modules/project/project_confirmed.html.twig', $render);

        return new Response($rendered);
    }

}