<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Extension;


use Contao\FrontendUser;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use XRayLP\LearningCenterBundle\Entity\Member;

class MemberExtension extends \Twig_Extension
{
    protected $tokenStorage;

    protected $doctrine;

    public function __construct(TokenStorage $tokenStorage, Registry $doctrine)
    {
        $this->tokenStorage = $tokenStorage;
        $this->doctrine = $doctrine;
    }

    public function getName()
    {
        return 'member_extension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_Function('member', array($this, 'getMember'))
        );
    }

    public function getMember()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof FrontendUser)
        {
            return $this->doctrine->getRepository(Member::class)->findOneById($user->id);
        }
    }
}