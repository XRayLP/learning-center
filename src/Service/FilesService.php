<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;


use Contao\FilesModel;
use Contao\FrontendUser;
use Contao\MemberModel;
use Doctrine\ORM\EntityManager;
use http\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FilesService
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(UrlGeneratorInterface $router, EntityManager $entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates the File Grid for the Filemanager by an Parent Folder ID and a User to check whether the User has access to the folder.
     *
     * @param $fid
     * @param $objUser FrontendUser|MemberModel
     * @return mixed
     * @throws \Exception
     */
    public function createFileGrid($fid, $objUser)
    {
        $objFolder = $this->getFolderForFilemanager($fid, $objUser);

        //has the user access to a home folder?
        if ($objUser->assignDir && $objUser->homeDir && !$this->isFolderEmpty($objFolder))
        {
            $objFiles = FilesModel::findByPid($objFolder->uuid);

            //process for every file in the collection
            while ($objFiles->next()) {
                if ($objFiles->type == 'folder') {
                    $objFolder = new \Folder($objFiles->path);

                    //url for subfolders
                    $strHref = $this->router->generate('learningcenter_files', array('fid' => $objFiles->id));

                    //adds folder to array
                    $files[$objFolder->path] = array
                    (
                        'id' => $objFiles->id,
                        'uuid' => $objFiles->uuid,
                        'type' => $objFiles->type,
                        'time' => date("d. M Y", $objFolder->mtime),
                        'path' => $objFolder->path,
                        'name' => $objFolder->filename,
                        'href' => $strHref,
                        'shared' => $objFiles->shared,
                        'shared_group' => $objFiles->shared_groups
                    );
                } else {
                    $objFile = new \File($objFiles->path);

                    $strHref = $this->router->generate('learningcenter_files.download', array('fid' => $objFiles->id));

                    //add file to array
                    $files[$objFiles->path] = array
                    (
                        'id' => $objFiles->id,
                        'uuid' => $objFiles->uuid,
                        'time' => date("d. M Y", $objFile->mtime),
                        'type' => $objFiles->type,
                        'name' => $objFile->basename,
                        'title' => \StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['download'], $objFile->basename)),
                        'href' => $strHref,
                        'filesize' => '',
                        'icon' => \Image::getPath($objFile->icon),
                        'extension' => $objFile->extension,
                        'path' => $objFile->dirname,
                        'shared' => $objFiles->shared,
                        'shared_group' => $objFiles->shared_groups
                    );
                }
            }
        }
        return $files;
    }

    /**
     * @param $file UploadedFile
     * @param $fid int
     * @param $objUser \FrontendUser|MemberModel
     * @return string
     * @throws \Exception
     */
    public function saveUploadedFile($file, $fid, $objUser)
    {
        $objParentFolder = $this->getFolderForFilemanager($fid, $objUser);
        //get the path for saving the file
        $savePath = $objParentFolder->path;
        //original filename
        $filename = $file->getClientOriginalName();

        //moves the file from tmp to the path
        $file->move('../'.$savePath, $filename);

        $strFile = $savePath . '/' . $filename;

        if (\Dbafs::shouldBeSynchronized($strFile))
        {
        $objModel = \FilesModel::findByPath($strFile);

            if ($objModel === null)
            {
                $objModel = \Dbafs::addResource($strFile);
            }

            // Update the hash of the target folder
            \Dbafs::updateFolderHashes($savePath);
        }

    }

    /**
     * @param $name string
     * @param $fid int
     * @param $objUser \FrontendUser|MemberModel
     * @return string
     * @throws \Exception
     */
    public function createFolder($name, $fid, $objUser)
    {
        $objParentFolder = $this->getFolderForFilemanager($fid, $objUser);
        $strFile = $objParentFolder->path.'/'.$name;
        $fs = new Filesystem();
        //creates the folder
        $fs->mkdir('../'.$strFile);

        //creates database entry
        if (\Dbafs::shouldBeSynchronized($strFile))
        {
            $objModel = \FilesModel::findByPath($strFile);

            if ($objModel === null)
            {
                $objModel = \Dbafs::addResource($strFile);
            }

            // Update the hash of the target folder
            \Dbafs::updateFolderHashes($objParentFolder->path);
        }
    }

    /**
     * deletes a file (only database entry)
     *
     * @param $id
     */
    public function deleteFile($id)
    {
        $strFile = FilesModel::findById($id)->path;
        \Dbafs::deleteResource($strFile);
    }

    /**
     * shares a file with a group
     *
     * @param $id int
     * @param $gid int
     */
    public function shareFile($id, $gid)
    {
        //gets the file object
        $objFile = FilesModel::findById($id);

        //checks whether the file is already shared
        if ($objFile->shared == 1) {
            //unshares the file
            $objFile->shared = 0;
            $objFile->shared_groups = null;
        } else {
            $objFile->shared = 1;
            $objFile->shared_groups = serialize($gid);
        }
        $objFile->save();

        //subfiles of folder are also shared
        if ($objFile->type == 'folder'){
            //gets all subfiles
            $objSubfiles = FilesModel::findMultipleByBasepath($objFile->path.'/');
            if ($objSubfiles !== null) {
                while ($objSubfiles->next())
                {
                    //checks whether the file is already shared
                    if ($objFile->shared == 1) {
                        //unshares the file
                        $objSubfiles->shared = 0;
                        $objSubfiles->shared_groups = null;
                    } else {
                        $objSubfiles->shared = 1;
                        $objSubfiles->shared_groups = serialize($gid);
                    }
                    $objSubfiles->save();
                }
            }
        }
    }

    /**
     * Returns the Folder Object, which should be used for the File Grid.
     *
     * @param $fid int
     * @param $objUser \FrontendUser|MemberModel
     * @return FilesModel
     */
    public function getFolderForFilemanager($fid, $objUser)
    {
        //check on integer
        if (isset($fid) && is_numeric($fid))
        {
            $objFolder = FilesModel::findById($fid);

            if ($objFolder->type == 'folder')
            {
                return $objFolder;
            }
        }

        return FilesModel::findByUuid($objUser->homeDir);

    }

    /**
     * DOESN'T WORK YET
     *
     * @param $objFile FilesModel
     * @param $objUser FrontendUser|MemberModel
     * @return bool
     */
    private function hasUserAccessToFileOrFolder($objFile, $objUser)
    {
        if (strpos($objFile->path, FilesModel::findByUuid($objUser->homeDir)->path))
        {
            return true;
        }
    }

    /**
     * Check whether a folder is empty or has subfiles/subfolders
     *
     * @param $objFolder
     * @return bool
     */
    private function isFolderEmpty($objFolder)
    {
        $objFiles = FilesModel::findByPid($objFolder->uuid);
        if ($objFiles === null) {
            return true;
        } else {
            return false;
        }

    }
}