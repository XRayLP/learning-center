<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */


namespace App\XRayLP\LearningCenterBundle\Security;

use App\XRayLP\LearningCenterBundle\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ParticipantProvider implements ParticipantProviderInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $securityContext;

    protected $doctrine;

    public function __construct(TokenStorageInterface $securityContext, RegistryInterface $doctrine)
    {
        $this->securityContext = $securityContext;

        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthenticatedParticipant()
    {
        $participant = $this->doctrine->getRepository(Member::class)->findOneById($this->securityContext->getToken()->getUser()->id);

        if (!$participant instanceof ParticipantInterface) {
            throw new AccessDeniedException('Must be logged in with a ParticipantInterface instance');
        }

        return $participant;
    }
}