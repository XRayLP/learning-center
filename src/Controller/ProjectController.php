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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XRayLP\LearningCenterBundle\Entity\Project;
use XRayLP\LearningCenterBundle\Form\MembersType;
use XRayLP\LearningCenterBundle\Form\CreateProjectType;

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
            $projects = \System::getContainer()->get('learningcenter.project')->getProjectsOfUser($User);

            //Twig
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/project_list.html.twig',
                [
                    'projects'  => $projects
                ]
            );
            return new Response($rendered);

        } else {
            return new RedirectResponse('contao_frontend');
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
        $this->container->get('contao.framework')->initialize();

        $User = FrontendUser::getInstance();

        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $project = \System::getContainer()->get('learningcenter.project')->getProjectDetails($alias);
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/project_details.html.twig',
                [
                    'project' => $project
                ]
            );

            return new Response($rendered);
        } else {
            return new RedirectResponse('contao_frontend');
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
            $entityManager = $this->getDoctrine()->getManager();

            $objProject = new Project();

            $form = $this->createForm(CreateProjectType::class, $objProject);
            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid())
            {
                $project = $form->getData();
                $objProject->setTstamp(time());
                $objProject->setName($project->getName());
                $objProject->setAlias($project->getName());
                $objProject->setDescription($project->getDescription());

                $objGroup = new MemberGroupModel();
                $objGroup->name = 'project_'.$objProject->getAlias();
                $objGroup->tstamp = time();
                $objGroup->groupType = 6;
                $objGroup->save();
                foreach ($project->getGroupId() as $member)
                {
                    $objMember = MemberModel::findById($member);
                    System::getContainer()->get('learningcenter.users')->addMemberToGroup($objMember, $objGroup);
                }
                $objProject->setGroupId($objGroup->id);

                $entityManager->persist($objProject);
                $entityManager->flush();

                return $this->redirectToRoute('learningcenter_projects.details', array('alias' => $objProject->getAlias()));
            }
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/project_create.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );

            return new Response($rendered);
        } else {
            return new RedirectResponse('contao_frontend');
        }
    }
}