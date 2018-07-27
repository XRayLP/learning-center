<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Security\Authorization\Voter;


use Contao\FrontendUser;
use Contao\StringUtil;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use XRayLP\LearningCenterBundle\Entity\Project;

class ProjectVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const CREATE = 'create';
    const LEAD = 'lead';


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
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE, self::LEAD))){
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

        $project = $subject;

        switch ($attribute){
            case self::VIEW:
                return $this->canView($project, $user);
            case self::EDIT:
                return $this->canEdit($project, $user);
            case self::CREATE:
                return $this->canCreate($project, $user);
            case self::LEAD:
                return $this->canLead($project, $user);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Project $project,FrontendUser $user)
    {
        return in_array($project->getGroupId(), StringUtil::deserialize($user->groups)) || $user->memberType == 'ROLE_ADMIN';
    }

    private function canEdit(Project $project,FrontendUser $user)
    {
        return in_array($user->id, StringUtil::deserialize($project->getAdmins())) || ($user->id == $project->getLeader()) || ($user->memberType == 'ROLE_ADMIN');
    }

    private function canLead(Project $project,FrontendUser $user)
    {
        return $user->memberType == 'ROLE_TEACHER';
    }

    private function canCreate(Project $project,FrontendUser $user)
    {
        return true;
    }


}