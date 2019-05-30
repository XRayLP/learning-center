<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Form\DownloadFileType;
use App\XRayLP\LearningCenterBundle\Request\DownloadFileRequest;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Contao\FrontendUser;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\Form\CreateDirectoryType;
use App\XRayLP\LearningCenterBundle\Form\DeleteFileType;
use App\XRayLP\LearningCenterBundle\Form\FileUploadType;
use App\XRayLP\LearningCenterBundle\Form\ShareFileType;
use App\XRayLP\LearningCenterBundle\Form\UpdateShareFileType;
use App\XRayLP\LearningCenterBundle\LearningCenter\Filemanager\Filemanager;
use App\XRayLP\LearningCenterBundle\Request\CreateDirectoryRequest;
use App\XRayLP\LearningCenterBundle\Request\DeleteFileRequest;
use App\XRayLP\LearningCenterBundle\Request\ShareFileRequest;
use App\XRayLP\LearningCenterBundle\Request\UpdateShareFileRequest;
use App\XRayLP\LearningCenterBundle\Request\UploadFileRequest;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use ZipArchive;

class FilesController extends Controller
{
    private $filemanager;

    private $twig;

    private $em;

    public function __construct(Filemanager $filemanager, \Twig_Environment $twig, EntityManagerInterface $entityManager)
    {
        $this->filemanager = $filemanager;
        $this->twig = $twig;
        $this->em = $entityManager->getRepository(File::class);
    }

    /**
     * @param $fid
     * @return RedirectResponse|Response
     * @throws \Exception
     */
    public function mainAction($fid)
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $errors = array();
            $arrTwig = array();

            $uploadFileRequest = new UploadFileRequest();
            $createDirectoryRequest = new CreateDirectoryRequest();
            $downloadFileRequest = new DownloadFileRequest();
            $deleteFileRequest = new DeleteFileRequest();
            $shareFileRequest = new ShareFileRequest();
            $updateShareFileRequest = new UpdateShareFileRequest();

            //Upload Files
            $upload = $this->createForm(FileUploadType::class, $uploadFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.upload', array('fid' => $fid))
            ));
            $arrTwig['upload'] = $upload->createView();

            //Create Folders
            $folder = $this->createForm(CreateDirectoryType::class, $createDirectoryRequest, array(
                'action' => $this->generateUrl('learningcenter_files.folder', array('fid' => $fid))
            ));
            $arrTwig['create_directory'] = $folder->createView();

            //Download Files
            $download = $this->createForm(DownloadFileType::class, $downloadFileRequest, array(
               'action' => $this->generateUrl('learningcenter_files.download', array('fid' => $fid))
            ));
            $arrTwig['download'] = $download->createView();

            //Delete Files
            $delete = $this->createForm(DeleteFileType::class, $deleteFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.delete', array('fid' => $fid))
            ));
            $arrTwig['delete'] = $delete->createView();

            //Share Files
            $share = $this->createForm(ShareFileType::class, $shareFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.share', array('fid' => $fid))
            ));
            $arrTwig['share'] = $share->createView();

            //Deshare Files
            $editShare = $this->createForm(UpdateShareFileType::class, $updateShareFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.share.edit', array('fid' => $fid))
            ));
            $arrTwig['share_edit'] = $editShare->createView();


            if (isset($fid))
            {
                $this->filemanager->setCurDir($this->getDoctrine()->getRepository(File::class)->findOneById($fid));
            }
            $arrTwig['usedSpace'] = $this->filemanager->getUsedSpacePercent();
            $arrTwig['breadcrumb'] = $this->filemanager->generateBreadcrumb();
            $arrTwig['files'] = $this->filemanager->loadFiles();
            $arrTwig['fid'] = $fid;

            //Twig/Renderer
            $rendered = $this->twig->render('@LearningCenter/modules/files.html.twig', $arrTwig);
            return new Response($rendered);

        } else {
            return new RedirectResponse('learningcenter_login');
        }
    }

    public function uploadAction($fid, Request $request)
    {

        $output = array('uploaded' => false);

        $file = $request->files->get('file');

        if (isset($fid))
        {
            $this->filemanager->setCurDir($this->getDoctrine()->getRepository(File::class)->findOneById($fid));
        }

        $this->filemanager->uploadFile($file);

        $output['uploaded'] = true;
        $output['fileName'] = 'TEST';
        return new JsonResponse($output);
    }

    /**
     * for the folder form
     *
     * @param $fid
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function folderAction($fid, Request $request)
    {
        $createDirectoryRequest = new CreateDirectoryRequest();
        $folder = $this->createForm(CreateDirectoryType::class, $createDirectoryRequest);
        $folder->handleRequest($request);
        if ($folder->isSubmitted() && $folder->isValid())
        {
            $name = $createDirectoryRequest->getName();

            if (isset($fid))
            {
                $this->filemanager->setCurDir($this->getDoctrine()->getRepository(File::class)->findOneById($fid));
            }

            $this->filemanager->mkdir($name);

        }
        return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
    }

    /**
     * Removes a file with the delete form of the filemanager
     *
     * @param $fid
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction($fid, Request $request)
    {
        //handle form request
        $deleteFileRequest = new DeleteFileRequest();
        $delete = $this->createForm(DeleteFileType::class, $deleteFileRequest);
        $delete->handleRequest($request);

        if ($delete->isSubmitted() && $delete->isValid())
        {
            //get file ids
            $arrId = explode(",", $deleteFileRequest->getId());

            //get file entities from database
            $files = $this->getDoctrine()->getRepository(File::class)->findBy(['id' => $arrId]);

            //remove each file
            foreach($files as $file)
            {
                $this->filemanager->removeFile($file);
            }
        }
        return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
    }

    /**
     * downloads a file
     *
     * @param $fid
     * @return BinaryFileResponse|RedirectResponse
     */
    public function downloadAction($fid, Request $request)
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $downloadFileRequest = new DownloadFileRequest();
            $download = $this->createForm(DownloadFileType::class, $downloadFileRequest);
            $download->handleRequest($request);

            if ($download->isSubmitted() && $download->isValid()) {
                //get file ids
                $arrId = explode(",", $downloadFileRequest->getId());

                //get file entities from database
                $files = $this->getDoctrine()->getRepository(File::class)->findBy(['id' => $arrId]);

                //one file
                if (count($files) === 1 && $files[0]->getType() == 'file')
                {
                    $file = $files[0];
                    $displayName = $file->getName();
                    $response = new BinaryFileResponse('../'.$file->getPath());
                    $response->headers->set ( 'Content-Type', 'text/plain' );
                    $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $displayName );
                    return $response;
                } else {
                    $zipname = $this->getDoctrine()->getRepository(File::class)->findOneByUuid($files[0]->getPid())->getName();
                    $id = uniqid($zipname);
                    $zip = $this->filemanager->createZip($files, $id);
                    $response = new BinaryFileResponse('tmp/'.$id.'.zip');
                    $response->headers->set ( 'Content-Type', 'application/zip' );
                    $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $zipname.'.zip' );
                    return $response;
                }
            }

            return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
        } else {
            return new RedirectResponse('learningcenter_login');
        }
    }

    /**
     * for the share file form
     *
     * @param $fid
     * @param Request $request
     * @return RedirectResponse
     */
    public function shareAction($fid, Request $request)
    {
        $shareFileRequest = new ShareFileRequest();
        $share = $this->createForm(ShareFileType::class, $shareFileRequest);
        $share->handleRequest($request);
        if ($share->isSubmitted() && $share->isValid())
        {
            //get file ids
            $files = $shareFileRequest->getFile();
            $group = $shareFileRequest->getMemberGroups();

            //share each file
            foreach($files as $file)
            {
                $this->filemanager->shareFile($file, $group);
            }

        }
        return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
    }

    public function editShareAction($fid, Request $request)
    {
        $updateShareFileRequest = new UpdateShareFileRequest();
        $editShare = $this->createForm(UpdateShareFileType::class, $updateShareFileRequest);
        $editShare->handleRequest($request);
        if ($editShare->isSubmitted() && $editShare->isValid())
        {
            //get file ids
            $files = $updateShareFileRequest->getFile();

            //edit each share
            foreach($files as $file)
            {
                $this->filemanager->updateShareFile($file);
            }
        }
        return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
    }

    public function loadToolbar($fid)
    {
        if (isset($fid)) {
            $file = $this->getDoctrine()->getRepository(File::class)->findOneById($fid);
        } else {
            $file = null;
        }

        $toolbar = $this->filemanager->generateToolbar($file);

        //Twig/Renderer
        $rendered = $this->renderView('@LearningCenter/modules/filemanager/files.toolbar.html.twig', $toolbar);
        return new Response($rendered);
    }

    public function loadFiles($fid)
    {
        if (isset($fid))
        {
            $this->filemanager->setCurDir($this->getDoctrine()->getRepository(File::class)->findOneById($fid));
        }
        $rendered = $this->renderView('@LearningCenter/modules/filemanager/files.files.html.twig', array(
            'files' => $this->filemanager->loadFiles(),
        ));
        return new Response(json_encode($this->filemanager->loadFiles()));
    }

    /**
     * @Route("/learningcenter/shares", methods={"GET", "POST"}, name="lc_shares", options={"expose"=true})
     */
    public function getShares(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $shares = [];

            $ids = $request->get('file_ids');

            $files = $this->em->findBy(['id' => $ids]);

            //get common shares of several files
            for ($i = 0; $i < count($files); $i++) {
                $file = $files[$i];
                if ($i === 0) {
                    $shares = $file->getSharedGroups()->toArray();
                    dump($shares);
                } else {
                    //file hasn't got any shares
                    if ($file->getSharedGroups()->count() === 0) {
                        $shares = [];
                    } else {
                        foreach ($shares as $key => $share) {
                            foreach ($file->getSharedGroups()->toArray() as $group) {
                                if ($group !== $share) {
                                    unset($shares[$key]);
                                }
                            }
                        }
                    }
                }
            }

            //serialize
            if ($shares) {
                $encoders = [
                    new JsonEncoder(),
                ];
                $normalizer = [
                    (new ObjectNormalizer())
                        ->setIgnoredAttributes(['members'])
                    ,
                ];
                $serializer = new Serializer($normalizer, $encoders);

                $data = $serializer->serialize($shares, 'json');

                return new JsonResponse($data, 200, [], true);
            }
        }

        return new JsonResponse([
           'type' => 'error',
           'message' => 'AJAX only'
        ]);
    }

    /**
     * @Route("/learningcenter/shares/remove", methods={"GET", "POST"}, name="lc_shares_remove", options={"expose"=true})
     */
    public function removeShare(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $emMemberGroup = $this->getDoctrine()->getRepository(MemberGroup::class);

            $file_ids = $request->get('file_ids');
            $share_id = $request->get('share_id');

            $files = $this->em->findBy(['id' => $file_ids]);
            $share = $emMemberGroup->findOneBy(['id' => $share_id]);

            foreach ($files as $file)
            {
                $this->filemanager->removeShareFile($file, $share);
            }

            return new JsonResponse([
                'type' => 'success',
                'message' => 'Share was removed!'
            ]);

        }

        return new JsonResponse([
            'type' => 'error',
            'message' => 'AJAX only'
        ]);
    }
}