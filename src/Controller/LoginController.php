<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Translation\TranslatorInterface;

class LoginController extends Controller
{
    public function loginAction()
    {
        //Check if the User isn't granted
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            //Exception Variables
            $exception = $this->get('security.authentication_utils')->getLastAuthenticationError();
            $hasError = false;
            $message = '';

            //Get Exceptions
            if ($exception instanceof LockedException)
            {
                $hasError = true;
                $message = $this->get('translator')->trans('This account has been locked! You can log in again in %d minutes.', $exception->getLockedMinutes());
            }
            elseif ($exception instanceof AuthenticationException)
            {
                $hasError = true;
                $message = $this->get('translator')->trans('Login failed (note that usernames and passwords are case-sensitive)!');
            }

            //Twig
            $twigRenderer = $this->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/security/login.html.twig', array(
                'hasError'  => $hasError,
                'message'   => $message
            ));
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter');
        }
    }

    public function logoutAction()
    {
        return $this->redirectToRoute('contao_frontend_logout');
    }
}