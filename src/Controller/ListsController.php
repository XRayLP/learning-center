<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */


namespace XRayLP\LearningCenterBundle\Controller;

use Contao\MemberModel;
use Contao\System;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ListsController extends Controller
{
    /**
     * A Student List
     *
     * @return RedirectResponse|Response
     */
    public function studentAction()
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
        $objMembers = MemberModel::findAll();
        while ($objMembers->next())
        {
            if ($objMembers->avatar == 0)
            {
                System::getContainer()->get('learningcenter.users')->createAvatar($objMembers);
            }
            $members[] = array(
                'firstname' => $objMembers->firstname,
                'lastname'  => $objMembers->lastname,
                'avatar'    => "/bundles/learningcenter/avatar/$objMembers->id.png",
                'url'       => $this->generateUrl('learningcenter_user.details', array('username' => $objMembers->username))
            );
        }

        //Twig
        $twigRenderer = \System::getContainer()->get('templating');
        $rendered = $twigRenderer->render('@LearningCenter/modules/user_list.html.twig',
            [
                'members'  => $members
            ]
        );
        return new Response($rendered);
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    //User Details
    public function detailsAction($username)
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $member = System::getContainer()->get('learningcenter.users')->getMemberDetails($username);
            //Twig
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/user_details.html.twig',
                [
                    'member'  => $member
                ]
            );

            return new Response($rendered);
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }

    }
}