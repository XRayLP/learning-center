<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */
namespace App\XRayLP\LearningCenterBundle\Controller;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;
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
class ProjectChatController extends AbstractController
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
     * @Route("/learningcenter/projects/{id}/chat", name="lc_projects_chat")
     * @param Project $project
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function chat(Project $project, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                $member = $this->doctrine->getRepository(Member::class)->findOneById($this->getUser()->id);
                $thread = $this->provider->getThread($project->getThread()->getId());
                $replyMessageRequest = new ReplyMessageRequest();
                $form = $this->createForm(ReplyMessageFormType::class, $replyMessageRequest);
                $rendered = $this->renderView('@LearningCenter/modules/project/chat.html.twig', array(
                    'form'      => $form->createView(),
                    'thread'    => $thread,
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
     * @Route("/learningcenter/projects/{id}/chat/send", name="lc_projects_chat_send")
     * @param Project $project
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function sendMessage(Project $project, Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {
            if ($this->isGranted('project.view', $project)) {
                $member = $this->doctrine->getRepository(Member::class)->findOneById($this->getUser()->id);
                $thread = $this->provider->getThread($project->getThread()->getId());
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
                return $this->redirectToRoute('lc_projects_list');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }
}