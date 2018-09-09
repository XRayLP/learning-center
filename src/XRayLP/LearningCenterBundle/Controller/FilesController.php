<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Controller;


use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\FilesModel;
use Contao\FrontendUser;
use http\Exception;
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

class FilesController extends Controller
{
    private $filemanager;

    private $twig;

    public function __construct(Filemanager $filemanager, \Twig_Environment $twig)
    {
        $this->filemanager = $filemanager;
        $this->twig = $twig;
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
            $deleteFileRequest = new DeleteFileRequest();
            $shareFileRequest = new ShareFileRequest();
            $updateShareFileRequest = new UpdateShareFileRequest();

            //Forms
            $upload = $this->createForm(FileUploadType::class, $uploadFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.upload', array('fid' => $fid))
            ));
            $arrTwig['upload'] = $upload->createView();

            $folder = $this->createForm(CreateDirectoryType::class, $createDirectoryRequest, array(
                'action' => $this->generateUrl('learningcenter_files.folder', array('fid' => $fid))
            ));
            $arrTwig['create_directory'] = $folder->createView();

            $delete = $this->createForm(DeleteFileType::class, $deleteFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.delete', array('fid' => $fid))
            ));
            $arrTwig['delete'] = $delete->createView();

            /*try {
                $share = $this->createForm(ShareFileType::class, $objFile, array(
                    'action' => $this->generateUrl('learningcenter_files.share', array('fid' => $fid))
                ));
                $arrTwig['share'] = $share->createView();
                $arrTwig['isShare'] = true;
            } catch (\Exception $e) {
                array_push($errors, $e->getMessage());
                $arrTwig['isShare'] = false;
            }*/

            $share = $this->createForm(ShareFileType::class, $shareFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.share', array('fid' => $fid))
            ));
            $arrTwig['share'] = $share->createView();

            $editShare = $this->createForm(UpdateShareFileType::class, $updateShareFileRequest, array(
                'action' => $this->generateUrl('learningcenter_files.share.edit', array('fid' => $fid))
            ));
            $arrTwig['share_edit'] = $editShare->createView();


            if (isset($fid))
            {
                $this->filemanager->setCurDir($this->getDoctrine()->getRepository(File::class)->findOneById($fid));
            }
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
        /*$uploadFileRequest = new UploadFileRequest();
        $upload = $this->createForm(FileUploadType::class, $uploadFileRequest);
        $upload->handleRequest($request);*/

        $output = array('uploaded' => false);

        $file = $request->files->get('file');

        if (isset($fid))
        {
            $this->filemanager->setCurDir($this->getDoctrine()->getRepository(File::class)->findOneById($fid));
        }

        $this->filemanager->uploadFile($file);

        /*if ($upload->isSubmitted() && $upload->isValid())
        {

        }*/
        $output['uploaded'] = true;
        $output['fileName'] = 'TEST';
        return new JsonResponse($output);
        //return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
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
     * for the delete form
     *
     * @param $fid
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction($fid, Request $request)
    {
        $deleteFileRequest = new DeleteFileRequest();
        $delete = $this->createForm(DeleteFileType::class, $deleteFileRequest);
        $delete->handleRequest($request);

        if ($delete->isSubmitted() && $delete->isValid())
        {
            $id = $deleteFileRequest->getId();

            $file = $this->getDoctrine()->getRepository(File::class)->findOneById($id);

            $this->filemanager->removeFile($file);

        }
        return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
    }

    /**
     * downloads a file
     *
     * @param $fid
     * @return BinaryFileResponse|RedirectResponse
     */
    public function downloadAction($fid)
    {
        //Check if the User isn't granted
        if (\System::getContainer()->get('security.authorization_checker')->isGranted('ROLE_MEMBER'))
        {
            $objFile = FilesModel::findById($fid);
            $displayName = $objFile->name;
            $response = new BinaryFileResponse('../'.$objFile->path);
            $response->headers->set ( 'Content-Type', 'text/plain' );
            $response->setContentDisposition ( ResponseHeaderBag::DISPOSITION_ATTACHMENT, $displayName );
            return $response;


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
            $file = $shareFileRequest->getFile();
            $group = $shareFileRequest->getMemberGroups();

            $this->filemanager->shareFile($file, $group);

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
            $file = $updateShareFileRequest->getFile();

            $this->filemanager->updateShareFile($file);
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
}