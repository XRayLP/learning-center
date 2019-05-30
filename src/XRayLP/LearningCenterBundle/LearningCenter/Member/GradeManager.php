<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;


use App\XRayLP\LearningCenterBundle\Entity\Grade;
use App\XRayLP\LearningCenterBundle\Entity\Message;
use App\XRayLP\LearningCenterBundle\Entity\MessageMetadata;
use App\XRayLP\LearningCenterBundle\Entity\Thread;
use App\XRayLP\LearningCenterBundle\Entity\ThreadMetadata;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Translation\TranslatorInterface;

class GradeManager extends MemberGroupManager
{
    /**
     * @var Grade $grade
     */
    private $grade;

    private $doctrine;

    private $entityManager;

    public function __construct(TranslatorInterface $translator, RegistryInterface $doctrine, EntityManagerInterface $entityManager)
    {
        parent::__construct($translator);

        $this->doctrine = $doctrine;
        $this->entityManager = $entityManager;
    }

    public function setGrade(Grade $grade)
    {
        $this->grade = $grade;
    }

    public function deleteThread()
    {
        $thread = $this->grade->getThread();

        //deletes the thread entry from grade object
        $this->grade->setThread(null);
        $this->entityManager->persist($this->grade);
        $this->entityManager->flush();

        //delete thread
        if (isset($thread))
        {
            $messages = $this->doctrine->getRepository(Message::class)->findBy(['thread' => $thread]);

            dump($messages);

            foreach ($messages as $message)
            {
                //delete all metadata of message
                $message_metadata = $this->doctrine->getRepository(MessageMetadata::class)->findBy(['message' => $message]);
                foreach ($message_metadata as $metadata)
                {
                    $this->entityManager->remove($metadata);
                    $this->entityManager->flush();
                }

                //delete message
                $this->entityManager->remove($message);
                $this->entityManager->flush();
            }

            $thread_metadata = $this->doctrine->getRepository(ThreadMetadata::class)->findBy(['thread' => $thread]);

            //delete all metadata
            foreach ($thread_metadata as $metadata)
            {
                $this->entityManager->remove($metadata);
                $this->entityManager->flush();
            }

            dump($thread);

            //delete thread
            $this->entityManager->remove($thread);
            $this->entityManager->flush();
        }
    }

    public function getGradename()
    {
        $grade_trans = $this->translator->trans('grade',[],'grade').' ';

        $grade = $this->grade;
        $gradeLevel = $grade->getGradeLevel()->getGradeNumber();
        $gradeSuffix = $grade->getSuffix();

        if (is_numeric($gradeSuffix)){
            $gradeName = $grade_trans.$gradeLevel.'-'.$gradeSuffix;
        } elseif (ctype_alpha($gradeSuffix)) {
            $gradeName = $grade_trans.$gradeLevel.$gradeSuffix;
        } else {
            $gradeName = $grade_trans.$gradeLevel.'_'.$gradeSuffix;
        }
        return $gradeName;
    }

    public function getGradeNumber()
    {
        $grade = $this->grade;
        $gradeLevel = $grade->getGradeLevel()->getGradeNumber();
        $gradeSuffix = $grade->getSuffix();

        if (is_numeric($gradeSuffix)) {
            $gradeNumber = $gradeLevel . '-' . $gradeSuffix;
        } elseif (ctype_alpha($gradeSuffix)) {
            $gradeNumber = $gradeLevel . $gradeSuffix;
        } else {
            $gradeNumber = $gradeLevel . '_' . $gradeSuffix;
        }
        return $gradeNumber;
    }
}