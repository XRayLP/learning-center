<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Security\Authorization\Voter;


use App\XRayLP\LearningCenterBundle\Entity\Member;
use Contao\FrontendUser;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemberVoter extends Voter
{

    private $memberRepository;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->memberRepository = $doctrine->getRepository(Member::class);
    }

    //Member specific
    const CREATE = 'member.create';
    const EDIT = 'member.edit';
    const REMOVE = 'member.remove';

    //Project specific
    const PROJECT_CREATE = 'member.project.create';
    const PROJECT_LIST = 'member.project.list';
    const PROJECT_LIST_ALL = 'member.project.list.all';

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
        if (!in_array($attribute, array(self::EDIT, self::CREATE, self::REMOVE, self::PROJECT_CREATE, self::PROJECT_LIST, self::PROJECT_LIST_ALL))){
            return false;
        }

        if (!$subject instanceof Member){
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
        //convert Contao Model to Doctrine Entity
        $user = $token->getUser();
        if (!$user instanceof FrontendUser) {
            return false;
        }
        $currentMember = $this->memberRepository->findOneById($user->id);

        $targetMember = $subject;

        switch ($attribute){
            case self::EDIT:
                return $this->canEdit($targetMember, $currentMember);
            case self::CREATE:
                return $this->canCreate($targetMember, $currentMember);
            case self::REMOVE:
                return $this->canRemove($targetMember, $currentMember);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Member $targetMember, Member $currentMember)
    {
        return $currentMember->isAdmin() || $currentMember === $targetMember;
    }

    private function canCreate(Member $targetMember, Member $currentMember)
    {
        return $currentMember->isAdmin();
    }

    private function canRemove(Member $targetMember, Member $currentMember)
    {
        return $currentMember->isAdmin();
    }
}