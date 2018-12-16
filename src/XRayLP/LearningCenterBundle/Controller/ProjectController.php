<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use App\XRayLP\LearningCenterBundle\Event\ProjectEventEvent;
use App\XRayLP\LearningCenterBundle\Event\ProjectMemberEvent;
use Contao\FrontendUser;
use Doctrine\ORM\EntityManagerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;
use App\XRayLP\LearningCenterBundle\Entity\Calendar;
use App\XRayLP\LearningCenterBundle\Entity\Event;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Entity\Project;
use App\XRayLP\LearningCenterBundle\Event\Events;
use App\XRayLP\LearningCenterBundle\Event\ProjectEvent;
use App\XRayLP\LearningCenterBundle\Form\CreateEventType;
use App\XRayLP\LearningCenterBundle\Form\CreateProjectType;
use App\XRayLP\LearningCenterBundle\Form\UpdateProjectType;
use App\XRayLP\LearningCenterBundle\LearningCenter\Project\ProjectMember;
use App\XRayLP\LearningCenterBundle\Request\CreateEventRequest;
use App\XRayLP\LearningCenterBundle\Request\CreateProjectRequest;
use App\XRayLP\LearningCenterBundle\Request\UpdateProjectRequest;
use Symfony\Component\Validator\Constraints\Time;

class ProjectController extends AbstractController
{
    private $eventDispatcher;

    private $translator;

    private $entityManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, TranslatorInterface $translator, EntityManagerInterface $entityManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->entityManager = $entityManager;
    }


    /**
     * List all Projects of the current User.
     *
     * @Route("/learningcenter/projects", name="lc_projects_list")
     *
     * @return RedirectResponse|Response
     */
    public function listProjects()
    {
        //Check if the User isn't granted
        if ($this->isGranted('ROLE_MEMBER'))
        {
            $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(array('id' => FrontendUser::getInstance()->id));
            $projects = $member->getProjects();

            //no projects
            if (empty($projects)) {
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    array(
                        'alert' => 'info',
                        'title' => '',
                        'message' => $this->translator->trans('project.no.projects')
                    )
                );
                $showProjects = false;
            } else {
                $showProjects = true;
            }


            //Twig
            $rendered = $this->renderView('@LearningCenter/modules/project_list.html.twig',
                [
                    'projects'  => $projects,
                    'showProjects' => $showProjects
                ]
            );
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter_login');
        }

    }

    /**
     * Form for creating an Project.
     *
     * @Route("/learningcenter/projects/create", name="lc_projects_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createProject(Request $request)
    {
        //check whether user is granted
        if ($this->isGranted('ROLE_MEMBER')) {
            //check users permission to create projects
            if ($this->isGranted('project.create')) {

                $project = new Project();
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
                    $currentUser = $this->getDoctrine()->getRepository(Member::class)->findOneById($this->getUser()->id);

                    //Project Entity
                    $project = new Project();
                    $project->setName($createProjectRequest->getName());
                    $project->setDescription($createProjectRequest->getDescription());
                    $project->setPublic($createProjectRequest->getPublic());
                    //user is leader when he can lead or the chosen teacher will be leader
                    if ($this->isGranted('project.lead')){
                        $project->setLeader($currentUser);
                        $project->setConfirmed(1);
                    } else {
                        $project->setLeader($createProjectRequest->getLeader());
                        $project->addAdmin($currentUser);
                        $project->setConfirmed(0);
                    }

                    //creates new group for the project
                    $group = new MemberGroup();
                    $group->setTstamp(time());
                    $group->setName($project->getName());
                    $group->setGroupType(4);
                    $entityManager->persist($group);
                    $entityManager->flush();

                    //save group in db
                    $group->addMember($currentUser);
                    if (!$this->isGranted('project.lead', $project)) {
                        $group->addMember($createProjectRequest->getLeader());
                    }

                    //save project with group
                    $project->setGroupId($group);
                    $entityManager->persist($project);
                    $entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_CREATE_SUCCESS_EVENT, new ProjectEvent($project));

                    return $this->redirectToRoute('lc_projects_detail', ['id' => $project->getId()]);
                }

                $rendered = $this->renderView('@LearningCenter/modules/project/project_create.html.twig',
                    [
                        'form'  => $form->createView(),
                    ]
                );

                return new Response($rendered);

            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * Project Details
     *
     * @Route("/learningcenter/projects/{id}", name="lc_projects_detail", requirements={"id"="\d+"})
     *
     * @param Project $project
     * @return RedirectResponse|Response
     */
    public function projectDetails(Project $project)
    {
        if ($this->isGranted('ROLE_MEMBER'))
        {
            if ($this->isGranted('project.view', $project)) {

                $this->eventDispatcher->dispatch(Events::PROJECT_PRE_LOAD, (new ProjectEvent($project)));

                $rendered = $this->renderView('@LearningCenter/modules/project/project_details.html.twig',
                    [
                        'project' => $project,
                    ]
                );

                return new Response($rendered);

            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }

    }

    /**
     * Project Members
     *
     * @Route("/learningcenter/projects/{id}/members", name="lc_projects_members", requirements={"id"="\d+"})
     *
     * @param Project $project
     * @return RedirectResponse|Response
     */
    public function projectMembers(Project $project)
    {
        if ($this->isGranted('ROLE_MEMBER')){
            if ($this->isGranted('project.view', $project))
            {
                $members = $project->getGroupId()->getMembers();
                $projectMembers = array();

                //create project Member Object for twig view
                foreach ($members as $member)
                {
                    $projectMembers[] = new ProjectMember($project, $member);
                }

                $this->sortMembersByNameAndRank($projectMembers);

                $rendered = $this->renderView('@LearningCenter/modules/project/project_members.html.twig',
                    [
                        'project'           => $project,
                        'projectMembers'    => $projectMembers,
                    ]
                );

                return new Response($rendered);

            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * Add new Members to a Project
     *
     * @Route("/learningcenter/projects/{id}/members/add", name="lc_projects_members_add", requirements={"id"="\d+"})
     *
     * @param Project $project
     * @return RedirectResponse|Response
     */
    public function projectMembersAddInterface(Project $project)
    {
        if ($this->isGranted('ROLE_MEMBER')){
            if ($this->isGranted('project.view', $project))
            {

                $rendered = $this->renderView('@LearningCenter/modules/project/project_members_add.html.twig',
                    [
                        'project'           => $project,
                    ]
                );

                return new Response($rendered);

            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * Searches for Members and returns them as an ajax request.
     *
     * @Route("/learningcenter/projects/{id}/members/get", methods={"POST", "GET"}, name="lc_projects_members_get", options={"expose"=true})
     * @param Project $project
     * @param Request $request
     * @return JsonResponse
     */
    public function getMembers(Project $project, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getRepository(Member::class);
            $projectMembers = [];

            $phrase = $request->get('phrase');

            //the keyword '#' returns all users
            if ($phrase == '#') {
                $members = $em->findAll();
            } else {
                $members = $em->findAllLike($phrase);
            }

            foreach ($members as $member)
            {
                $projectMembers[] = new ProjectMember($project, $member);
            }

            if (!empty($projectMembers)) {
                $encoders = [
                    new JsonEncoder(),
                ];
                $normalizer = [
                    (new ObjectNormalizer())
                        ->setIgnoredAttributes([
                            'homeDir', 'groups', 'password', 'activation', 'permissions', 'projects', 'session', 'start', 'stop', 'thread'
                        ])

                ];
                $serializer = new Serializer($normalizer, $encoders);

                $data = $serializer->serialize($projectMembers, 'json');

                return new JsonResponse($data, 200, [], true);
            }
            return new JsonResponse([
                'type' => 'error',
                'message' => 'TEST',
            ]);
        }

        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only',
        ]);
    }


    /**
     * @Route("/learningcenter/projects/{id}/members/remove/{member_id}", name="lc_projects_members_remove")
     * @Entity("member", expr="repository.find(member_id)")
     *
     * @param Project $project
     * @param Member $member
     * @param Request $request
     * @return RedirectResponse
     */
    public function projectMembersRemove(Project $project, Member $member, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                $projectMember = new ProjectMember($project, $member);
                dump($projectMember);
                if ($this->isGranted('project.removeMember', $projectMember))
                {
                    //removing member is admin, then delete him too
                    if ($projectMember->isAdmin())
                    {
                        $project->removeAdmin($member);
                    }
                    $project->getGroupId()->removeMember($member);

                    $this->entityManager->persist($project);
                    $this->entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_MEMBERS_REMOVE_SUCCESS_EVENT, (new ProjectMemberEvent($project, $member)));
                }
                return $this->redirectToRoute('lc_projects_members', ['id' => $project->getId()]);
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }


    /**
     * @Route("/learningcenter/projects/{id}/members/promoteAdmin/{member_id}", name="lc_projects_members_promote_admin")
     * @Entity("member", expr="repository.find(member_id)")
     *
     * @param Project $project
     * @param Member $member
     * @param Request $request
     * @return RedirectResponse
     */
    public function projectMembersPromoteToAdmin(Project $project, Member $member, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                $projectMember = new ProjectMember($project, $member);
                dump($projectMember);
                if ($this->isGranted('project.promoteToAdmin', $projectMember))
                {
                    $project->addAdmin($member);

                    $this->entityManager->persist($project);
                    $this->entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_MEMBERS_PROMOTE_ADMIN_SUCCESS_EVENT, (new ProjectMemberEvent($project, $member)));
                }
                return $this->redirectToRoute('lc_projects_members', ['id' => $project->getId()]);
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/projects/{id}/members/promoteLeader/{member_id}", name="lc_projects_members_promote_leader")
     * @Entity("member", expr="repository.find(member_id)")
     *
     * @param Project $project
     * @param Member $member
     * @param Request $request
     * @return RedirectResponse
     */
    public function projectMembersPromoteToLeader(Project $project, Member $member, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                $projectMember = new ProjectMember($project, $member);
                dump($projectMember);
                if ($this->isGranted('project.promoteToLeader', $projectMember))
                {
                    $leader = $project->getLeader();
                    $project->setLeader($member);
                    $project->addAdmin($leader);

                    //removing member is admin, then delete him too
                    if ($projectMember->isAdmin())
                    {
                        $project->removeAdmin($member);
                    }

                    $this->entityManager->persist($project);
                    $this->entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_MEMBERS_PROMOTE_LEADER_SUCCESS_EVENT, (new ProjectMemberEvent($project, $member)));
                }
                return $this->redirectToRoute('lc_projects_members', ['id' => $project->getId()]);
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/projects/{id}/members/degrade/{member_id}", name="lc_projects_members_degrade")
     * @Entity("member", expr="repository.find(member_id)")
     *
     * @param Project $project
     * @param Member $member
     * @param Request $request
     * @return RedirectResponse
     */
    public function projectMembersDegrade(Project $project, Member $member, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                $projectMember = new ProjectMember($project, $member);
                if ($this->isGranted('project.degradeToMember', $projectMember))
                {

                    $project->removeAdmin($member);

                    $this->entityManager->persist($project);
                    $this->entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_MEMBERS_DEGRADE_SUCCESS_EVENT, (new ProjectMemberEvent($project, $member)));
                }
                return $this->redirectToRoute('lc_projects_members', ['id' => $project->getId()]);
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/projects/{id}/members/add/{member_id}", name="lc_projects_member_add", options={"expose"=true})
     * @Entity("member", expr="repository.find(member_id)")
     *
     * @param Project $project
     * @param Member $member
     * @return RedirectResponse
     */
    public function projectMembersAdd(Project $project, Member $member)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                if (true)
                {
                    $member->addGroup($project->getGroupId());

                    $this->entityManager->persist($project);
                    $this->entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_MEMBERS_ADD_SUCCESS_EVENT, (new ProjectMemberEvent($project, $member)));
                }
                return $this->redirectToRoute('lc_projects_members', ['id' => $project->getId()]);
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/projects/{id}/settings", name="lc_projects_settings")
     *
     * @param Project $project
     * @param Request $request
     * @return Response
     */
    public function settings(Project $project, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                if ($this->isGranted('project.edit', $project))
                {
                    //get Request
                    $updateProjectRequest = UpdateProjectRequest::fromProject($project);

                    $form = $this->createForm(UpdateProjectType::class, $updateProjectRequest);

                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid())
                    {
                        $project->setDescription($updateProjectRequest->getDescription());
                        $project->setName($updateProjectRequest->getName());

                        $this->entityManager->persist($project);
                        $this->entityManager->flush();
                        $this->eventDispatcher->dispatch(Events::PROJECT_UPDATE_SUCCESS_EVENT, (new ProjectEvent($project)));
                    }

                    //delete the project
                    if ($this->isGranted('project.remove', $project))
                    {

                    }

                    $rendered = $this->render('@LearningCenter/modules/project/project_settings.html.twig', [
                        'form' => $form->createView(),
                        'project' => $project,
                    ]);

                    return new Response($rendered);

                } else {
                    return $this->redirectToRoute('lc_projects_detail');
                }
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/projects/{id}/delete", name="lc_projects_delete")
     *
     * @param Project $project
     * @param Request $request
     * @return Response
     */
    public function deleteProject(Project $project, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                //delete the project
                if ($this->isGranted('project.remove', $project))
                {
                    $this->eventDispatcher->dispatch(Events::PROJECT_PRE_DELETE_EVENT, (new ProjectEvent($project)));

                    $this->entityManager->remove($project);
                    $this->entityManager->persist($project);
                    $this->entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_POST_DELETE_SUCCESS_EVENT, (new ProjectEvent($project)));

                    return $this->redirectToRoute('lc_projects_list');

                } else {
                    return $this->redirectToRoute('lc_projects_detail');
                }
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * Form for creating an Project.
     *
     * @Route("/learningcenter/projects/create", name="lc_projects_create")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        //check whether user is granted
        if ($this->isGranted('ROLE_MEMBER')) {
            //check users permission to create projects
            if ($this->isGranted('project.create')) {

                $project = new Project();
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
                    $currentUser = $this->getDoctrine()->getRepository(Member::class)->findOneById($this->getUser()->id);

                    //Project Entity
                    $project = new Project();
                    $project->setName($createProjectRequest->getName());
                    $project->setDescription($createProjectRequest->getDescription());
                    $project->setPublic($createProjectRequest->getPublic());
                    //user is leader when he can lead or the chosen teacher will be leader
                    if ($this->isGranted('project.lead')){
                        $project->setLeader($currentUser);
                        $project->setConfirmed(1);
                    } else {
                        $project->setLeader($createProjectRequest->getLeader());
                        $project->addAdmin($currentUser);
                        $project->setConfirmed(0);
                    }

                    //creates new group for the project
                    $group = new MemberGroup();
                    $group->setTstamp(time());
                    $group->setName($project->getName());
                    $group->setGroupType(4);
                    $entityManager->persist($group);
                    $entityManager->flush();

                    //save group in db
                    $group->addMember($currentUser);
                    if (!$this->isGranted('project.lead', $project)) {
                        $group->addMember($createProjectRequest->getLeader());
                    }

                    //save project with group
                    $project->setGroupId($group);
                    $entityManager->persist($project);
                    $entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_CREATE_SUCCESS_EVENT, new ProjectEvent($project));

                    return $this->redirectToRoute('lc_projects_detail', ['id' => $project->getId()]);
                }

                $rendered = $this->renderView('@LearningCenter/modules/project/project_create.html.twig',
                    [
                        'form'  => $form->createView(),
                    ]
                );

                return new Response($rendered);

            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * Form for creating an Project.
     *
     * @Route("/learningcenter/projects/{id}/confirm", name="lc_projects_confirm")
     *
     * @param Project $project
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function confirmAction(Project $project, Request $request)
    {
        $render = array();
        $data = array();
        $translator = $this->translator;
        $entityManager = $this->getDoctrine()->getManager();
        $render['project'] = $project;

        if ($request->get('confirm') === "1") {
            $project->setConfirmed(1);
            $entityManager->persist($project);
            $entityManager->flush();
        } elseif ($request->get('confirm') === "0") {
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('lc_projects_delete', ['id' => $project->getId()]);
        }

        if ($project->getConfirmed()) {
            $message = $translator->trans('project.confirmed', array('%name%' => $project->getName()));
            $render['confirmed'] = true;
        } else {
            $message = $translator->trans('project.need.confirm', array('%name%' => $project->getName()));
            $render['confirmed'] = false;
            if ($this->isGranted('project.confirm', $project)) {
                $form = $this->createFormBuilder($data)->getForm();
                $form->handleRequest($request);
                $render['form'] = $form->createView();
            }
        }
        $render['message'] = $message;


        $rendered = $this->renderView('@LearningCenter/modules/project/project_confirmed.html.twig', $render);

        return new Response($rendered);
    }

    /**
     * @Route("/learningcenter/projects/{id}/events", name="lc_projects_events")
     *
     * @param Project $project
     * @return RedirectResponse|Response
     */
    public function listEvents(Project $project)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {

                $events = $project->getEvents();
                $calendar = $project->getCalendar();

                //generate dates
                foreach ($events as $event)
                {
                    $event->setStartDate(date('d.m.Y H:i', $event->getStartDate()));
                    $event->setStartTime(date('d.m.Y H:i', $event->getStartTime()));
                    $event->setEndDate(date('d.m.Y H:i', $event->getEndDate()));
                    $event->setEndTime(date('d.m.Y H:i', $event->getEndTime()));
                }

                $rendered = $this->renderView('@LearningCenter/modules/project/project_events.html.twig', array(
                    'events'  => $events,
                    'calendar' => $calendar,
                    'project' => $project
                ));
                return new Response($rendered);
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/projects/{id}/events/add", name="lc_projects_events_add")
     *
     * @param Project $project
     * @param Request $request
     * @return Response
     */
    public function createEvent(Project $project, Request $request)
    {

        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                if ($this->isGranted('project.event.add', $project))
                {
                    $createEventRequest = new CreateEventRequest();

                    $form = $this->createForm(CreateEventType::class, $createEventRequest);

                    $form->handleRequest($request);

                    if ($form->isSubmitted() && $form->isValid())
                    {
                        //creates event from the request
                        $event = new Event();
                        $event->setTitle($createEventRequest->getName());
                        $event->setTstamp(time());
                        $event->setStartDate($createEventRequest->getStartDate()->getTimestamp());
                        $event->setEndDate($createEventRequest->getEndDate()->getTimestamp());
                        $event->setStartTime($createEventRequest->getStartTime()->getTimestamp() + $event->getStartDate());
                        $event->setEndTime($createEventRequest->getEndTime()->getTimestamp() + $event->getEndDate());
                        $event->setAddress($createEventRequest->getAddress());
                        $event->setPid($project->getCalendar());

                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($event);
                        $entityManager->flush();

                        $this->eventDispatcher->dispatch(Events::PROJECT_EVENTS_ADD_SUCCESS_EVENT, (new ProjectEventEvent($project, $event)));
                        return $this->redirectToRoute('lc_projects_events', ['id' => $project->getId()]);
                    }

                    $rendered = $this->renderView('@LearningCenter/modules/project/project_create_event.html.twig', array(
                        'form' => $form->createView(),
                        'project' => $project
                    ));

                    return new Response($rendered);
                } else {
                    return $this->redirectToRoute('lc_projects_events', ['id' => $project->getId()]);
                }
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/projects/{id}/events/{event_id}/remove", name="lc_projects_events_remove")
     * @Entity("event", expr="repository.find(event_id)")
     *
     * @param Project $project
     * @param Event $event
     * @return RedirectResponse
     */
    public function removeEvent(Project $project, Event $event)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                if ($this->isGranted('project.event.remove', $project))
                {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($event);
                    $entityManager->flush();

                    $this->eventDispatcher->dispatch(Events::PROJECT_EVENTS_REMOVE_SUCCESS_EVENT, (new ProjectEventEvent($project, $event)));
                    return $this->redirectToRoute('lc_projects_events', ['id' => $project->getId()]);

                } else {
                    return $this->redirectToRoute('lc_projects_events', ['id' => $project->getId()]);
                }
            } else {
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @param ProjectMember[] $projectMember
     */
    private function sortMembersByNameAndRank($projectMember)
    {
        //sort
        $aIndex = 1;
        $lIndex = 0;
        //sort by type (admin/leader/member)
        for ($i=0; $i < count($projectMember); $i++) {
            if ($projectMember[$i]->isLeader() === true) {
                $tmp = $projectMember[$lIndex];
                $projectMember[$lIndex] = $projectMember[$i];
                $projectMember[$i] = $tmp;
            } elseif ($projectMember[$i]->isAdmin() === true) {
                if ($aIndex == $i) {
                    $tmp = $projectMember[$aIndex];
                    $projectMember[$aIndex] = $projectMember[$i];
                    $projectMember[$i] = $tmp;
                }
                $aIndex++;
            }
        }

        //sort the admins
        for ($i=$lIndex+1; $i < $aIndex; $i++) {
            $min = $i;
            for($a=$i; $a < count($projectMember); $a++) {
                if ($projectMember[$a]->getMember()->getLastname() < $projectMember[$min]->getMember()->getLastname()) {
                    $min = $a;
                }
            }
            $tmp = $projectMember[$i];
            $projectMember[$i] = $projectMember[$min];
            $projectMember[$min] = $tmp;
        }

        //sort the members
        for ($i=$aIndex; $i < count($projectMember); $i++) {
            $min = $i;
            for($a=$i; $a < count($projectMember); $a++) {
                if ($projectMember[$a]->getMember()->getLastname() < $projectMember[$min]->getMember()->getLastname()) {
                    $min = $a;
                }
            }
            $tmp = $projectMember[$i];
            $projectMember[$i] = $projectMember[$min];
            $projectMember[$min] = $tmp;
        }
    }

}