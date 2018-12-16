<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Form\ReplyMessageFormType;
use App\XRayLP\LearningCenterBundle\Request\ReplyMessageRequest;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class GroupChatController extends AbstractController
{
    private $eventDispatcher;
    private $translator;
    private $doctrine;
    private $provider;
    private $composer;
    private $sender;
    private $threadManager;
    public function __construct(EventDispatcherInterface $eventDispatcher, TranslatorInterface $translator, RegistryInterface $doctrine, ProviderInterface $provider, ComposerInterface $composer, SenderInterface $sender, ThreadManagerInterface $threadManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->doctrine = $doctrine;
        $this->provider = $provider;
        $this->composer = $composer;
        $this->sender = $sender;
        $this->threadManager = $threadManager;
    }

    /**
     * @Route("/learningcenter/groups/{id}/chat", name="lc_groups_chat")
     * @param MemberGroup $memberGroup
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function chat(MemberGroup $memberGroup, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            $member = $this->doctrine->getRepository(Member::class)->findOneById($this->getUser()->id);
            $thread = $this->provider->getThread($memberGroup->getThread()->getId());
            $replyMessageRequest = new ReplyMessageRequest();
            $form = $this->createForm(ReplyMessageFormType::class, $replyMessageRequest);
            $rendered = $this->renderView('@LearningCenter/modules/project/chat.html.twig', array(
                'form'      => $form->createView(),
                'thread'    => $thread,
                'group' => $memberGroup
            ));
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    /**
     * @Route("/learningcenter/groups/{id}/chat/send", name="lc_groups_chat_send")
     * @param MemberGroup $memberGroup
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function sendMessage(MemberGroup $memberGroup, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            $member = $this->doctrine->getRepository(Member::class)->findOneById($this->getUser()->id);
            $thread = $this->provider->getThread($memberGroup->getThread()->getId());
            $replyMessageRequest = new ReplyMessageRequest();
            $form = $this->createForm(ReplyMessageFormType::class, $replyMessageRequest);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $message = $this->composer->reply($thread)
                    ->setSender($member)
                    ->setBody($replyMessageRequest->getBody())
                    ->getMessage();
                $this->sender->send($message);
            }
            return $this->redirectToRoute('lc_projects_chat', ['id' => $project->getId()]);

        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }
}