<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Controller;


use Contao\FilesModel;
use Contao\FrontendUser;
use http\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use XRayLP\LearningCenterBundle\Entity\File;
use XRayLP\LearningCenterBundle\Form\CreateFolderType;
use XRayLP\LearningCenterBundle\Form\DeleteFileType;
use XRayLP\LearningCenterBundle\Form\FileUploadType;
use XRayLP\LearningCenterBundle\Form\ShareFileType;

class FilesController extends Controller
{
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
            $User = FrontendUser::getInstance();

            $objFile = new File();
            //Forms
            $upload = $this->createForm(FileUploadType::class, $objFile, array(
                'action' => $this->generateUrl('learningcenter_files.upload', array('fid' => $fid))
            ));
            $folder = $this->createForm(CreateFolderType::class, $objFile, array(
                'action' => $this->generateUrl('learningcenter_files.folder', array('fid' => $fid))
            ));
            $delete = $this->createForm(DeleteFileType::class, $objFile, array(
                'action' => $this->generateUrl('learningcenter_files.delete', array('fid' => $fid))
            ));
            $share = $this->createForm(ShareFileType::class, $objFile, array(
                'action' => $this->generateUrl('learningcenter_files.share', array('fid' => $fid))
            ));


            $files = \System::getContainer()->get('learningcenter.files')->createFileGrid($fid, $User);

            $breadcrumb = \System::getContainer()->get('learningcenter.files')->createFilemanagerBreadcrumb($fid, $User);
            //Twig/Renderer
            $twigRenderer = \System::getContainer()->get('templating');
            $rendered = $twigRenderer->render('@LearningCenter/modules/files.html.twig', array(
                'upload' => $upload->createView(),
                'folder' => $folder->createView(),
                'delete' => $delete->createView(),
                'share'  => $share->createView(),
                'files'  => $files,
                'breadcrumb' => $breadcrumb
            ));
            return new Response($rendered);

        } else {
            return new RedirectResponse('learningcenter_login');
        }
    }

    /**
     * Process the Upload Form
     * @param $fid
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function uploadAction($fid, Request $request)
    {
        $User = FrontendUser::getInstance();
        $objFile = new File();
        $upload = $this->createForm(FileUploadType::class, $objFile);
        $upload->handleRequest($request);
        if ($upload->isSubmitted() && $upload->isValid())
        {
            $file = $objFile->getPath();
            \System::getContainer()->get('learningcenter.files')->saveUploadedFile($file, $fid, $User);

        }
        return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
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
        $User = FrontendUser::getInstance();
        $objFile = new File();
        $folder = $this->createForm(CreateFolderType::class, $objFile);
        $folder->handleRequest($request);
        if ($folder->isSubmitted() && $folder->isValid())
        {
            $name = $objFile->getName();
            \System::getContainer()->get('learningcenter.files')->createFolder($name, $fid, $User);

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
        $objFile = new File();
        $delete = $this->createForm(DeleteFileType::class, $objFile);
        $delete->handleRequest($request);
        if ($delete->isSubmitted() && $delete->isValid())
        {
            $id = $objFile->getId();
            \System::getContainer()->get('learningcenter.files')->deleteFile($id);

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
        $objFile = new File();
        $share = $this->createForm(ShareFileType::class, $objFile);
        $share->handleRequest($request);
        if ($share->isSubmitted() && $share->isValid())
        {
            $id = $objFile->getId();
            $courses = $objFile->getSharedGroups();
            \System::getContainer()->get('learningcenter.files')->shareFile($id, $courses);

        }
        return $this->redirectToRoute('learningcenter_files', array('fid' => $fid));
    }

    public function detailAction($fid)
    {

    }
}