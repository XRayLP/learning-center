<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;

use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\LearningCenter\Filemanager\Filemanager;
use App\XRayLP\LearningCenterBundle\Util\ByteConverter;
use Contao\Config;
use Contao\FilesModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LearningCenterController
 *
 * @package App\XRayLP\LearningCenterBundle\Controller
 */
class LearningCenterController extends Controller
{

    // selbst erstellter Filemanager Service
    private $filemanager;

    // Twig, um Zugriff auf erweiterte Funktionen für Templates zu haben
    private $twig;

    // Entity Manager, um auf Entities der Datenbank zuzugreifen
    private $em;

    /*
    * Variablen werden die einzelnen Services zugeordnet
    */
    public function __construct(Filemanager $filemanager, \Twig_Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->filemanager = $filemanager; // Filemanager Service wird deklariert
        $this->twig = $twig;
        $this->em = $entityManager->getRepository(File::class);
    }

    /**
     * Haupt Dashboard, auf das der Nutzer nach dem Login weitergeleitet wird.
     *
     * Route, unter der der Controller zu erreichen ist.
     * @Route("/learningcenter", name="lc_dashboard")
     *
     * @return RedirectResponse|Response
     */
    public function dashboard()
    {
        // Überprüfung, ob der Nutzer als Frontend Mitglied angemeldet ist.
        if ($this->isGranted('ROLE_MEMBER'))
        {

            // Member Enitity des angemledeten Nutzers wird über den Doctrine Service aus der Datenbank geladen
            $member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(array('id' => $this->getUser()->id));

            // Prozent des genutzten Speichers in der Cloud in Abhängigkeit der Speichergrenze
            $storage[0] = $this->filemanager->getUsedSpacePercent();

            // Array aus genutztem Speicherplatz und maximalen Speicherplatz in absoluten Zahlen
            $sizes = $this->filemanager->getUsedSpace();

            dump($sizes);

            // Konvertierung des Speohcerplatz Arrays in komfortbale Einheiten
            foreach ($sizes as $size)
            {
                // automatische Konvertierung nach Byte Größe
                $size = ByteConverter::byteAutoConvert($size);

                // Erstellung eines String Arrays
                $storage[] = $size['size'].' '.strtoupper($size['unit']);
            }

            //rendern des Twig Templates über den Twig Service
            $rendered = $this->renderView('@LearningCenter/modules/dashboard.html.twig', array(
                'name' => $member->getFirstname().' '.$member->getLastname(), // Mitgliedsname
                'schoolname' => Config::get('name'), // Schulname
                'logo'  => FilesModel::findByUuid(Config::get('logo')), // Schullogo
                'storage' => $storage, //Speicherplatz Array
            ));

            // das gerenderte Twig Template wird als Response an den Nutzer gesendet
            return new Response($rendered);

        } else {
            // ist der Nutzer nicht als Frontend Mitglied angemeldet, wird er zur Login Seite weitergeleitet
            return $this->redirectToRoute('learningcenter_login');
        }

    }


    /**
     * Redirect URLs with a Trailing Slash
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeTrailingSlashAction(Request $request)
    {
        $pathInfo   = $request->getPathInfo();
        $requestUri = $request->getRequestUri();

        $url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);

        return $this->redirect($url, 301);

    }
}