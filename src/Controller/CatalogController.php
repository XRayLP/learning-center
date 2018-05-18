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

class CatalogController extends Controller
{
    public function mainAction()
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $User = \FrontendUser::getInstance();

            //$files = \System::getContainer()->get('learningcenter.files')->createCatalogTimeline($User);

            $catalog = \System::getContainer()->get('learningcenter.catalog');
            $catalog->setMember($User);
            $files = $catalog->loadFiles();
            $errors = $catalog->getErrors();


            //Twig
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/catalog_timeline.html.twig', array(
                'files' => $files,
                'errors' => $errors
            ));
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }
}