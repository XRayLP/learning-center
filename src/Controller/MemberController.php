<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 17.07.2018
 * Time: 10:54
 */

namespace XRayLP\LearningCenterBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use XRayLP\LearningCenterBundle\Entity\Member;
use XRayLP\LearningCenterBundle\Entity\MemberGroup;
use XRayLP\LearningCenterBundle\Entity\Project;
use XRayLP\LearningCenterBundle\Member\MemberGroupManagement;
use XRayLP\LearningCenterBundle\Member\MemberManagement;
use XRayLP\LearningCenterBundle\Project\ProjectMemberManagement;

class MemberController extends Controller
{
    /**
     * Searches for Members and returns them as an ajax request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxSearchAction(Request $request) {
        $jsonData = array();
        $idx = 0;

        //input
        $phrase = $request->get('phrase');

        //get member group to see whether is already a member of the group
        $memberGroup = $this->getDoctrine()->getRepository(MemberGroup::class)->findOneBy(array('id' => $request->get('group')));


        //the keyword '#' returns all users
        if ($phrase == '#') {
            $members = $this->getDoctrine()->getRepository(Member::class)->findAll();
        } else {
            $members = $this->getDoctrine()->getRepository(Member::class)->findAllLike($phrase);
        }

        //creates the array that will return with member infos
        foreach($members as $member) {
            $memberM = new MemberManagement($member);
            $temp = array(
                'id' => $member->getId(),
                'firstname' => $member->getFirstname(),
                'lastname' => $member->getLastname(),
                'avatar'    => $memberM->getAvatar(),
                'isMember' => $memberM->isMemberOf($memberGroup)
            );
            $jsonData[$idx++] = $temp;
        }
        return new JsonResponse($jsonData);

    }

    /**
     * Adds a member to a group through an ajax request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddAction(Request $request) {

        //user and group id
        $user = $request->get('user');
        $group = $request->get('group');

        $doctrine = $this->getDoctrine();

        //adding the user to the project group
        try {
            $member = $doctrine->getRepository(Member::class)->findOneBy(array('id' => $user));
            $memberGroup = $doctrine->getRepository(MemberGroup::class)->findOneBy(array('id' => $group));

            $member->addGroup($memberGroup);
            $doctrine->getManager()->persist($member);
            $doctrine->getManager()->flush();

            $message = 'The User was added to the project.';
        } catch (\Exception $e) {
            $message = 'Something went wrong. Try it again after reloading the page.';
        }

        $jsonData = $message;

        return new JsonResponse($jsonData);
    }
}