<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 22.07.2018
 * Time: 15:33
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Member;

use Contao\Config;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ContaoUserProvider implements UserProviderInterface
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var string
     */
    private $userClass;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @param ContaoFrameworkInterface $framework
     * @param SessionInterface         $session
     * @param string                   $userClass
     * @param LoggerInterface|null     $logger
     *
     * @throws \RuntimeException
     */
    public function __construct(ContaoFrameworkInterface $framework, SessionInterface $session, string $userClass, LoggerInterface $logger = null)
    {

        $this->framework = $framework;
        $this->session = $session;
        $this->userClass = $userClass;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username): User
    {
        $this->framework->initialize();

        /** @var User $adapter */
        $adapter = $this->framework->getAdapter($this->userClass);
        $user = $adapter->loadUserByUsername($username);

        if (is_a($user, $this->userClass)) {
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('Could not find user "%s"', $username));
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!is_a($user, $this->userClass)) {
            throw new UnsupportedUserException(sprintf('Unsupported class "%s".', \get_class($user)));
        }

        /** @var User $user */
        $user = $this->loadUserByUsername($user->username);


        $this->validateSessionLifetime($user);
        $this->triggerPostAuthenticateHook($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class): bool
    {
        return $this->userClass === $class;
    }

    /**
     * Validates the session lifetime and logs the user out if the session has expired.
     *
     * @param User $user
     *
     * @throws UsernameNotFoundException
     */
    private function validateSessionLifetime(User $user): void
    {
        if (!$this->session->isStarted()) {
            return;
        }

        /** @var Config $config */
        $config = $this->framework->getAdapter(Config::class);
        $timeout = (int) $config->get('sessionTimeout');

        if ($timeout > 0 && (time() - $this->session->getMetadataBag()->getLastUsed()) < $timeout) {
            return;
        }

        if (null !== $this->logger) {
            $this->logger->info(
                sprintf('User "%s" has been logged out automatically due to inactivity', $user->username),
                ['contao' => new ContaoContext(__METHOD__, ContaoContext::ACCESS, $user->username)]
            );
        }

        throw new UsernameNotFoundException(
            sprintf('User "%s" has been logged out automatically due to inactivity.', $user->username)
        );
    }

    /**
     * Triggers the postAuthenticate hook.
     *
     * @param User $user
     */
    private function triggerPostAuthenticateHook(User $user): void
    {
        if (empty($GLOBALS['TL_HOOKS']['postAuthenticate']) || !\is_array($GLOBALS['TL_HOOKS']['postAuthenticate'])) {
            return;
        }

        @trigger_error('Using the "postAuthenticate" hook has been deprecated and will no longer work in Contao 5.0.', E_USER_DEPRECATED);

        foreach ($GLOBALS['TL_HOOKS']['postAuthenticate'] as $callback) {
            $this->framework->createInstance($callback[0])->{$callback[1]}($user);
        }
    }
}
