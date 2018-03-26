<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;


use Contao\FilesModel;
use Contao\FrontendUser;
use Contao\MemberGroupModel;
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
                        'time' => date("d. M Y", $objFiles->tstamp),
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
                        'time' => date("d. M Y", $objFiles->tstamp),
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
        return $this->sortFilesArrayByTypeAndName($files);
    }

    /**
     * Creates a Breadcrumb to navigate through the filemanager
     *
     * @param $fid int
     * @param $objUser FrontendUser|MemberModel
     * @return array
     */
    public function createFilemanagerBreadcrumb($fid, $objUser)
    {
        $breadcrumb = array();

        //check if a subfolder
        if (is_numeric($fid)) {
            $currentPath = FilesModel::findById($fid)->path;
            $homePath = FilesModel::findByUuid($objUser->homeDir)->path;

            //create a link for every part of the current path until the homeDir is reached
            while ($homePath !== $currentPath)
            {
                $objFolder = FilesModel::findByPath($currentPath);
                $breadcrumb[] = array(
                    'name'  => $objFolder->name,
                    'href'  => $this->router->generate('learningcenter_files', array('fid' => $objFolder->id))
                );
                //cut one part of the string after the last "/" to check the next directory
                $rmLength = strrpos($currentPath, "/") - strlen($currentPath);
                $currentPath = substr($currentPath, 0, $rmLength);
            }
        }

        //adds the home path to the breadcrumb
        $breadcrumb[] = array(
            'name' => 'Home',
            'href'  => $this->router->generate('learningcenter_files')
        );

        //returns the array in reverse
        return array_reverse($breadcrumb);
    }

    /**
     * Creates an array of the shared files of a user
     *
     * @param $objUser FrontendUser|MemberModel
     * @return array
     */
    public function createCatalogTimeline($objUser)
    {
        $files = array();

        $objFiles = FilesModel::findBy('shared', 1);

        $arrGroups = \StringUtil::deserialize($objUser->groups);

        while ($objFiles->next())
        {
            if ($objFiles->shared == 1) {
                foreach ($arrGroups as $id) {
                    if ($id == unserialize($objFiles->shared_groups)) {
                        $strHref = $this->router->generate('learningcenter_files.download', array('fid' => $objFiles->id));
                        $strGroup = MemberGroupModel::findById($id)->name;

                        //add file to array
                        $files[] = array
                        (
                            'id' => $objFiles->id,
                            'tstamp' => $objFiles->shared_tstamp,
                            'uuid' => $objFiles->uuid,
                            'name' => $objFiles->name,
                            'href' => $strHref,
                            'filesize' => '',
                            'extension' => $objFiles->extension,
                            'shared' => $objFiles->shared,
                            'shared_group' => $strGroup,
                            'shared_time' => date('d.m.Y', $objFiles->shared_tstamp)
                        );
                    }
                }
            }
        }
        //sorts the files by tstamp
        for ($i=0; $i<count($files); $i++)
        {
            // position of the biggest element
            $minpos=$i;
            for ($j=$i+1; $j<count($files); $j++)
                if ($files[$j]['tstamp']>$files[$minpos]['tstamp'])
                    $minpos=$j;
            // change elements
            $tmp=$files[$minpos];
            $files[$minpos]=$files[$i];
            $files[$i]=$tmp;
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
            $objFile->shared_tstamp = null;
        } else {
            $objFile->shared = 1;
            $objFile->shared_groups = serialize($gid);
            $objFile->shared_tstamp = time();
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
                        $objSubfiles->shared_tstamp = null;
                    } else {
                        $objSubfiles->shared = 1;
                        $objSubfiles->shared_groups = serialize($gid);
                        $objSubfiles->shared_tstamp = time();
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

    /**
     * Sorts the files array for the filemanager by type and name
     *
     * @param $files
     * @return array
     */
    private function sortFilesArrayByTypeAndName($files)
    {
        if (isset($files))
        {
            $filesFolders = array();
            $filesFiles = array();

            //sorts the files by types
            foreach ($files as $file)
            {
                if ($file['type'] == 'folder')
                {
                    $filesFolders[] = $file;
                } else {
                    $filesFiles[] = $file;
                }
            }

            //sorts the folders by name
            for ($i=0; $i<count($filesFolders); $i++)
            {
                // position of the smallest element
                $minpos=$i;
                for ($j=$i+1; $j<count($filesFolders); $j++)
                    if ($filesFolders[$j]['name']<$filesFolders[$minpos]['name'])
                        $minpos=$j;
                // change elements
                $tmp=$filesFolders[$minpos];
                $filesFolders[$minpos]=$filesFolders[$i];
                $filesFolders[$i]=$tmp;
            }

            //sorts the files by name
            for ($i=0; $i<count($filesFiles); $i++)
            {
                // position of the smallest element
                $minpos=$i;
                for ($j=$i+1; $j<count($filesFiles); $j++)
                    if ($filesFiles[$j]['name']<$filesFiles[$minpos]['name'])
                        $minpos=$j;
                // change elements
                $tmp=$filesFiles[$minpos];
                $filesFiles[$minpos]=$filesFiles[$i];
                $filesFiles[$i]=$tmp;
            }

            //merge the two temporal arrays
            $files = array_merge($filesFolders, $filesFiles);
        }
        return $files;
    }
}