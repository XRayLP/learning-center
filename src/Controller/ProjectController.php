<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Controller;


use Contao\FrontendUser;
use Contao\MemberGroupModel;
use Contao\MemberModel;
use Contao\System;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XRayLP\LearningCenterBundle\Form\CreateProjectType;
use XRayLP\LearningCenterBundle\Service\Member;
use XRayLP\LearningCenterBundle\Service\Project;

class ProjectController extends Controller
{
    /**
     * A Project List
     *
     * @return RedirectResponse|Response
     */
    public function mainAction()
    {
        $this->container->get('contao.framework')->initialize();

        $User = FrontendUser::getInstance();

        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $errors = array();
            $member = new Member(FrontendUser::getInstance());
            $projects = array();
            $objProjects = $member->getProjects();

            if ($objProjects !== null) {
                while ($objProjects->next()) {
                    $url = System::getContainer()->get('router')->generate('learningcenter_projects.details', array('alias' => $objProjects->id));

                    $projects[] = array(
                        'id' => $objProjects->id,
                        'name' => $objProjects->projectName,
                        'description' => $objProjects->projectDescription,
                        'url' => $url
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
        $User = FrontendUser::getInstance();

        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $objProject = new Project(MemberGroupModel::findById($alias));

            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/project_details.html.twig',
                [
                    'project' => $objProject->getProjectDetails()
                ]
            );

            return new Response($rendered);
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * Create Projects
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $this->container->get('contao.framework')->initialize();
        $request->setRequestFormat('html');
        //$User = FrontendUser::getInstance();
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $data = array();
            $form = $this->createForm(CreateProjectType::class, $data);
            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid())
            {
                $project = $form->getData();
                $objProject = new Project();
                $objProject->getGroupModel()->tstamp = time();
                $objProject->getGroupModel()->name = 'Project_'.$project['name'];
                $objProject->getGroupModel()->projectName = $project['name'];
                $objProject->getGroupModel()->projectDescription = $project['description'];
                $objProject->getGroupModel()->save();

                foreach ($project['groupId'] as $member)
                {
                    $objMember = MemberModel::findById($member);
                    $objProject->addMemberToGroup($objMember);
                }

                return $this->redirectToRoute('learningcenter_projects.details', array('alias' => $objProject->getGroupModel()->id));
            }
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/project_create.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );

            return new Response($rendered);
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }
}