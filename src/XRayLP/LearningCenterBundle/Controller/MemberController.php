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

namespace App\XRayLP\LearningCenterBundle\Controller;


use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\Form\UpdateMemberType;
use App\XRayLP\LearningCenterBundle\Request\UpdateMemberRequest;
use function Clue\StreamFilter\fun;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Entity\Project;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\MemberGroupManagement;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\MemberManagement;
use App\XRayLP\LearningCenterBundle\LearningCenter\Project\ProjectMemberManagement;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MemberController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Frontend Members can edit their Profile
     *
     * @Route("/learningcenter/profile", name="lc_profile")
     * @param Request $request
     * @return Response
     */
    public function updateMember(Request $request)
    {
        $memberRepository = $this->getDoctrine()->getRepository(Member::class);
        $member = $memberRepository->findOneById($this->getUser()->id);

        //has right to edit the user
        if ($this->isGranted('member.edit', $member))
        {
            $memberRequest = UpdateMemberRequest::fromMember($member);

            $form = $this->createForm(UpdateMemberType::class, $memberRequest);
            $form->handleRequest($request);
            dump($memberRequest->getPassword());

            if ($form->isSubmitted() && $form->isValid())
            {
                dump($memberRequest->getPassword());
                //update member entity
                $member->updateRequest($memberRequest);

                $this->entityManager->persist($member);
                $this->entityManager->flush();
            }


            $rendered = $this->renderView('@LearningCenter/modules/member/edit_profile.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
            return new Response($rendered);
        }

        //redirect to homepage
        else {
            return $this->redirectToRoute('learningcenter');
        }

    }

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
     * Searches for Members and returns them as an ajax request.
     *
     * @Route("/learningcenter/members", methods={"POST", "GET"}, name="lc_members", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function getMembers(Request $request)
    {
        if ($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getRepository(Member::class);

            $phrase = $request->get('phrase');

            //the keyword '#' returns all users
            if ($phrase == '#') {
                $members = $em->findAll();
            } else {
                $members = $em->findAllLike($phrase);
            }
            $members = $em->findOneById(1);

            if ($members) {
                $encoders = [
                    new JsonEncoder(),
                ];
                $normalizer = [
                    (new ObjectNormalizer())
                        ->setIgnoredAttributes([
                            'homeDir', 'groups', 'password', 'activation', 'permissions', 'projects', 'session', 'start', 'stop'
                        ])

                ];
                $serializer = new Serializer($normalizer, $encoders);

                $data = $serializer->serialize($members, 'json');

                return new JsonResponse($data, 200, [], true);
            }
        }

        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only',
        ]);

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