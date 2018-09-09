<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use App\XRayLP\LearningCenterBundle\Service\Catalog;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CatalogController extends Controller
{
    public function mainAction(Catalog $catalog, TokenStorageInterface $tokenStorage)
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {

            //$files = \System::getContainer()->get('learningcenter.files')->createCatalogTimeline($User);

            //creates the catalog object
            $catalog->setMember($this->getDoctrine()->getRepository(Member::class)->findOneById($tokenStorage->getToken()->getUser()->id));
            $files = $catalog->loadFiles();
            $errors = $catalog->getErrors();



            $rendered = $this->renderView('@LearningCenter/modules/catalog_timeline.html.twig', array(
                'files' => $files,
                'errors' => $errors
            ));
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }
}