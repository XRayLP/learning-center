<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use System;

class LoginController extends Controller
{
    public function loginAction()
    {
        //Check if the User isn't granted
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            //Twig
            $twigRenderer = $this->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/security/login.html.twig');
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter');        }
    }

    public function logoutAction()
    {
        return $this->redirectToRoute('contao_frontend_logout');
    }
}