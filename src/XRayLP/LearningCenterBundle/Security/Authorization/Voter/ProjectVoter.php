<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Security\Authorization\Voter;


use Contao\FrontendUser;
use Contao\StringUtil;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\Project;

class ProjectVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const LEAD = 'lead';
    const CONFIRM = 'confirm';
    const ISADMIN = 'isAdmin';
    const ISLEADER = 'isLeader';
    const ISMEMBER = 'isMember';



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
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::LEAD, self::CONFIRM))){
            return false;
        }

        if (!$subject instanceof Project){
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

        $project = $subject;

        switch ($attribute){
            case self::VIEW:
                return $this->canView($project, $member);
            case self::EDIT:
                return $this->canEdit($project, $member);
            case self::CREATE:
                return $this->canCreate($project, $member);
            case self::LEAD:
                return $this->canLead($project, $member);
            case self::CONFIRM:
                return $this->canConfirm($project, $member);
            case self::ISADMIN:
                return $this->isAdmin($project, $member);
            case self::ISLEADER:
                return $this->isLeader($project, $member);
            case self::ISMEMBER:
                return $this->isMember($project, $member);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Project $project, Member $member)
    {
        return $this->isMember($project, $member);
    }

    private function canEdit(Project $project, Member $member)
    {
        return $this->isAdmin($project, $member) || $this->isLeader($project, $member);
    }

    private function canLead(Project $project, Member $member)
    {
        return $member->getMemberType() == 'ROLE_TEACHER';
    }

    private function canCreate(Project $project, Member $member)
    {
        return $member->getMemberType() == ('ROLE_TEACHER' || 'ROLE_STUDENT');
    }

    private function canConfirm(Project $project, Member $member)
    {
        return $this->isLeader($project, $member);
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