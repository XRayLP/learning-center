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
use XRayLP\LearningCenterBundle\Entity\Member;
use XRayLP\LearningCenterBundle\Member\MemberManagement;
use XRayLP\LearningCenterBundle\Service\FrontendMember;

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
        $objMembers = $this->getDoctrine()->getRepository(Member::class)->findAll();
        $members = array();
        foreach ($objMembers as $objMember)
        {
            $memberManagement = new MemberManagement($objMember);

            $members[] = array(
                'firstname' => $objMember->getFirstname(),
                'lastname'  => $objMember->getLastname(),
                'avatar'    => $memberManagement->getAvatar(),
                'url'       => $this->generateUrl('learningcenter_user.details', array('username' => $objMember->getUsername()))
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
            $objMember = $this->getDoctrine()->getRepository(Member::class)->findOneBy(array('username' => $username));
            $memberManagement = new MemberManagement($objMember);
            $member = array(
                'firstname' => $objMember->getFirstname(),
                'lastname'  => $objMember->getLastname(),
                'avatar'    => $memberManagement->getAvatar(true),
                'username'  => $objMember->getUsername(),
                'gender'    => $objMember->getGender(),
                'email'     => $objMember->getEmail(),
            );
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