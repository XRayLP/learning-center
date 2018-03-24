<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Controller;


use Contao\FrontendUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use XRayLP\LearningCenterBundle\Model\ProjectsModel;

class LearningCenterController extends Controller
{

    /**
     * SOON: Dashboard
     *
     * @return RedirectResponse|Response
     */
    public function mainAction()
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            //Twig
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/base.html.twig');
            return new Response($rendered);

        } else {
            return new RedirectResponse('contao_frontend');
        }

    }
}