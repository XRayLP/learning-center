<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Security\Authorization\Voter;


use App\XRayLP\LearningCenterBundle\LearningCenter\Project\ProjectMember;
use Contao\FrontendUser;
use Contao\StringUtil;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;

class ProjectMemberVoter extends Voter
{
    const PROMOTE_LEADER = 'project.promoteToLeader';
    const PROMOTE_ADMIN = 'project.promoteToAdmin';
    const DEGRADE_MEMBER = 'project.degradeToMember';
    const REMOVE_MEMBER = 'project.removeMember';


    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param $subject
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::PROMOTE_LEADER, self::PROMOTE_ADMIN, self::DEGRADE_MEMBER, self::REMOVE_MEMBER))){
            return false;
        }

        if (!$subject instanceof ProjectMember){
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
        $member = \System::getContainer()->get('doctrine')->getRepository(Member::class)->findOneById($user->id);

        if ($subject instanceof ProjectMember)
        {
            $project = $subject->getProject();
            $memberToChange = $subject->getMember();
        }

        switch ($attribute){
            case self::PROMOTE_LEADER:
                return $this->canPromoteToLeader($project, $member, $memberToChange);
            case self::PROMOTE_ADMIN:
                return $this->canPromoteToAdmin($project, $member, $memberToChange);
            case self::DEGRADE_MEMBER:
                return $this->canDegrade($project, $member, $memberToChange);
            case self::REMOVE_MEMBER:
                return $this->canRemoveMember($project, $member, $memberToChange);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canDegrade(Project $project, Member $member, Member $memberToDegrade)
    {
        if ($this->isLeader($project, $member) && $this->isAdmin($project, $memberToDegrade)) {
            return true;
        } else {
            return false;
        }
    }

    private function canPromoteToAdmin(Project $project, Member $member, Member $memberToPromote)
    {
        if ($this->isAdmin($project, $member) || $this->isLeader($project, $member)) {
            if (!$this->isAdmin($project, $memberToPromote) && !$this->isLeader($project, $memberToPromote)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function canPromoteToLeader(Project $project, Member $member, Member $memberToPromote)
    {
        if ($this->isLeader($project, $member) && $member !== $memberToPromote) {
            return true;
        } else {
            return false;
        }
    }

    private function canRemoveMember(Project $project, Member $member, Member $memberToRemove)
    {
        if ($member !== $memberToRemove) {
            if ($this->isLeader($project, $member)) {
                return true;
            } elseif ($this->isAdmin($project, $member) && !$this->isAdmin($project, $memberToRemove) && !$this->isLeader($project, $memberToRemove)) {
                return true;

            } else {
                return false;
            }
        } else {
            return false;
        }
    }

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