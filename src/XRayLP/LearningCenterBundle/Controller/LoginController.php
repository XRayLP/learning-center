<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;

use Contao\Config;
use Contao\FilesModel;
use Contao\RequestToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Translation\TranslatorInterfaceInterface;

class LoginController extends Controller
{

    public function loginAction()
    {
        //Check if the User isn't granted
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $logo['path'] = '/bundles/learningcenter/img/logo.png';


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

            $rendered = $this->renderView('@LearningCenter/security/login.html.twig', array(
                'token'     => $this->get('contao.csrf.token_manager')->getToken($this->getParameter('contao.csrf_token_name'))->getValue(),
                'hasError'  => $hasError,
                'message'   => $message,
                'logo'      => $logo
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