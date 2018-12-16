<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use App\XRayLP\LearningCenterBundle\Entity\Grade;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\GradeManager;
use App\XRayLP\LearningCenterBundle\LearningCenter\Member\MemberManager;
use Contao\FrontendUser;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class GradeController extends Controller
{
    private $gradeRepository;

    private $gradeManager;

    private $memberManager;

    private $flashMessage;

    private $translator;

    public function __construct(RegistryInterface $doctrine, GradeManager $gradeManager, MemberManager $memberManager, FlashBagInterface $flashBag, TranslatorInterface $translator)
    {
        $this->gradeRepository = $doctrine->getRepository(Grade::class);
        $this->gradeManager = $gradeManager;
        $this->memberManager = $memberManager;
        $this->flashMessage = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @Route("/learningcenter/grade", name="lc_grade", requirements={"id"="\d+"})
     * @return Response
     */
    public function dashboard()
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

        if (isset($grade) && $grade instanceof Grade) {
            $this->gradeManager->setGrade($grade);
            $gradeName = $this->gradeManager->getGradename();

            $teacher = $grade->getTutor();
            $this->memberManager->setMember($teacher);
            $avatar = $this->memberManager->getAvatar();


            //all members
            $members = $grade->getGroup()->getMembers();
            dump($grade);
            dump($members);
            for($i=0; $i < count($members); $i++)
            {
                $min = $i;
                for($j=$i+1; $j < count($members); $j++)
                {
                    if ($members[$min]->getLastname() < $members[$j]->getLastname())
                    {
                        $min = $j;
                    }
                }

                //exchange
                $temp = $members[$i];
                $members[$i] = $members[$min];
                $members[$min] = $temp;
            }
            //Twig
            $rendered = $this->renderView('@LearningCenter/modules/grade/dashboard.html.twig',
                [
                    'grade' => $grade,
                    'gradeName' => $gradeName,
                    'teacher'   => $teacher,
                    'teacherAvatar' => $avatar,
                    'students'  => $members,
                ]
            );
            return new Response($rendered);
        } else {
            $this->flashMessage->add('notice', array(
                'alert' => 'danger',
                'title' => '',
                'message' => $this->translator->trans('error.no.grade', [], 'grade')
            ));
            $this->redirectToRoute('lc_dashboard');
        }
    }

    /**
     * @Route("/learningcenter/mygrade/calendar", name="lc_grade_calendar")
     * @param Grade $grade
     */
    public function calendar(Grade $grade){

    }

    /**
     * @Route("/learningcenter/mygrade/chat", name="lc_grade_chat")
     * @param Grade $grade
     */
    public function chat(Grade $grade){

    }

}