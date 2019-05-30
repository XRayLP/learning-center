<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 12.01.19
 * Time: 16:52
 */

namespace App\XRayLP\LearningCenterBundle\Controller;

use App\XRayLP\LearningCenterBundle\Entity\Grade;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\GradeManager;
use Contao\FrontendUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;
use App\XRayLP\LearningCenterBundle\Form\ReplyMessageFormType;
use App\XRayLP\LearningCenterBundle\Request\ReplyMessageRequest;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class GradeChatController extends AbstractController
{

    private $eventDispatcher;
    private $translator;
    private $doctrine;
    private $provider;
    private $composer;
    private $sender;
    private $threadManager;
    private $gradeRepository;
    private $gradeManager;

    public function __construct(EventDispatcherInterface $eventDispatcher, TranslatorInterface $translator, RegistryInterface $doctrine, ProviderInterface $provider, ComposerInterface $composer, SenderInterface $sender, ThreadManagerInterface $threadManager, GradeManager $gradeManager)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->translator = $translator;
        $this->doctrine = $doctrine;
        $this->provider = $provider;
        $this->composer = $composer;
        $this->sender = $sender;
        $this->threadManager = $threadManager;
        $this->gradeRepository = $doctrine->getRepository(Grade::class);
        $this->gradeManager = $gradeManager;
    }
    /**
     * @Route("/learningcenter/grade/chat", name="lc_grade_chat")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function chat(Request $request)
    {
        $grade = $this->getGrade();

        $this->gradeManager->setGrade($grade);

        dump($grade->getThread()->getId());

        if (isset($grade) && $grade instanceof Grade) {
            $member = $this->doctrine->getRepository(Member::class)->findOneById($this->getUser()->id);
            //$thread = $this->provider->getThread($grade->getThread()->getId());
            $thread = $grade->getThread();
            $replyMessageRequest = new ReplyMessageRequest();
            $form = $this->createForm(ReplyMessageFormType::class, $replyMessageRequest);
            $rendered = $this->renderView('@LearningCenter/modules/grade/chat.html.twig', array(
                'form'      => $form->createView(),
                'thread'    => $thread,
                'gradeName' => $this->gradeManager->getGradename(),
                'grade' => $grade
            ));

            dump($rendered);

            return new Response($rendered);
        } else {
            return $this->redirectToRoute('lc_grade');
        }
    }
    /**
     * @Route("/learningcenter/grade/chat/send", name="lc_grade_chat_send")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function sendMessage(Request $request)
    {
        if ($this->isGranted('ROLE_MEMBER')) {

            $grade = $this->getGrade();

            if (isset($grade) && $grade instanceof Grade) {
                $member = $this->doctrine->getRepository(Member::class)->findOneById($this->getUser()->id);
                //$thread = $this->provider->getThread($grade->getThread()->getId());
                $thread = $grade->getThread();
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
                return $this->redirectToRoute('lc_grade_chat', ['id' => $grade->getId()]);
            } else {
                return $this->redirectToRoute('lc_dashboard');
            }
        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }

    private function getGrade()
    {
        //members
        $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(array('id' => FrontendUser::getInstance()->id));

        $groups = $member->getGroups();

        if ($groups instanceof ArrayCollection)
        {
            $groups = $groups->toArray();
        }
        foreach ($groups as $group){
            dump($group);
            if ($group instanceof MemberGroup && $group->getGroupType() == 2)
            {
                $grade = $this->gradeRepository->findOneBy(['group' => $group->getId()]);
            }
        }

        dump($grade);

        return $grade;
    }
}