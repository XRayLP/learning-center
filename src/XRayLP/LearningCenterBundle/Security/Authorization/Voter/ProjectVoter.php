<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Security\Authorization\Voter;


use Contao\FrontendUser;
use Contao\StringUtil;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;

class ProjectVoter extends Voter
{
    private $memberRepository;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->memberRepository = $doctrine->getRepository(Member::class);
    }

    //don't need an Project Object
    const USE = 'project';
    const CREATE = 'project.create';
    const LEAD = 'project.lead';
    const LIST_ALL = 'project.list.all';

    //need Project Object
    const VIEW = 'project.view';
    const EDIT = 'project.edit';
    const REMOVE = 'project.remove';
    const CONFIRM = 'project.confirm';

    //project events
    const EVENT_ADD = 'project.event.add';
    const EVENT_REMOVE = 'project.event.remove';



    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::REMOVE, self::CREATE, self::LEAD, self::CONFIRM, self::EVENT_ADD, self::EVENT_REMOVE))){
            return false;
        }

        if (!$subject instanceof Project && !in_array($attribute, [self::USE, self::CREATE, self::LEAD, self::LIST_ALL])){
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof FrontendUser) {
            return false;
        }
        $member = $this->memberRepository->findOneById($user->id);

        $project = $subject;

        switch ($attribute){
            case self::USE:
                return $this->canUse($member);
            case self::CREATE:
                return $this->canCreate($member);
            case self::LEAD:
                return $this->canLead($member);
            case self::LIST_ALL:
                return $this->canListAll($member);
            case self::VIEW:
                return $this->canView($project, $member);
            case self::EDIT:
                return $this->canEdit($project, $member);
            case self::REMOVE:
                return$this->canRemove($project, $member);
            case self::CONFIRM:
                return $this->canConfirm($project, $member);
            case self::EVENT_ADD:
                return $this->canAddEvent($project, $member);
            case self::EVENT_REMOVE:
                return $this->canRemoveEvent($project, $member);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canUse(Member $member)
    {
        return $member instanceof Member;
    }

    private function canCreate(Member $member)
    {
        return true;
    }

    private function canLead(Member $member)
    {
        return $member->isTeacher() || $member->isPlanner() || $member->isAdmin();
    }

    private function canListAll(Member $member)
    {
        return $member->isAdmin();
    }

    private function canView(Project $project, Member $member)
    {
        return $this->isMember($project, $member);
    }

    private function canEdit(Project $project, Member $member)
    {
        return $this->isAdmin($project, $member) || $this->isLeader($project, $member);
    }

    private function canRemove(Project $project, Member $member)
    {
        return $this->isLeader($project, $member);
    }

    private function canConfirm(Project $project, Member $member)
    {
        return $this->isLeader($project, $member);
    }

    private function canAddEvent(Project $project, Member $member)
    {
        return $this->isAdmin($project, $member) || $this->isLeader($project, $member);
    }

    private function canRemoveEvent(Project $project, Member $member)
    {
        return $this->isAdmin($project, $member) || $this->isLeader($project, $member);
    }


    //Helper Functions

    private function isLeader(Project $project, Member $member)
    {
        return ($this->isMember($project, $member)) && ($project->getLeader() == $member);
    }

    private function isAdmin(Project $project, Member $member)
    {
        return ($this->isMember($project, $member)) && ($project->getAdmins()->contains($member));
    }

    private function isMember(Project $project, Member $member)
    {
        //check whether user is already part of this project
        return $member->getGroups()->contains($project->getGroupId());
    }


}