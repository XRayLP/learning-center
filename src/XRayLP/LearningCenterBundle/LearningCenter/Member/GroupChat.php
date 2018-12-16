<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use FOS\MessageBundle\Composer\ComposerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\ProviderInterface;
use FOS\MessageBundle\Sender\SenderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface;

class GroupChat
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

    public function createGroupChat(MemberGroup $group) {
        if ($group->getThread() === null){
            //create new chat thread for project
            $thread = $this->threadManager->createThread();
            $thread->setSubject($group->getName().' Chat');
            $thread->setCreatedAt(new \DateTime());

            foreach ($group->getMembers() as $member)
            {
                $thread->addParticipant($member);
            }
        }
    }
}