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
use Symfony\Component\Translation\TranslatorInterface;

class CatalogController extends Controller
{

    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Catalog Timeline shows Shared Files for the Member
     *
     * TODO: Delete one's own Shares
     *
     * @param Catalog $catalog
     * @param TokenStorageInterface $tokenStorage
     * @return RedirectResponse|Response
     */
    public function mainAction(Catalog $catalog, TokenStorageInterface $tokenStorage)
    {
        //Check if the User isn't granted
        if ($this->isGranted('ROLE_MEMBER'))
        {
            //creates the catalog object
            $catalog->setMember($this->getDoctrine()->getRepository(Member::class)->findOneById($tokenStorage->getToken()->getUser()->id));
            $files = $catalog->loadFiles();

            //no shared files exist
            if (count($files) === 0){
                $this->get('session')->getFlashBag()->add(
                    'notice',
                    array(
                        'alert' => 'danger',
                        'title' => '',
                        'message' => $this->translator->trans('catalog.no.files')
                    )
                );
            }

            $rendered = $this->renderView('@LearningCenter/modules/catalog_timeline.html.twig', array(
                'files' => $files,
            ));
            return new Response($rendered);

        } else {
            return $this->redirectToRoute('learningcenter_login');
        }
    }
}