<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Service;


use Contao\Files;
use Contao\FilesModel;
use Contao\FrontendUser;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Filemanager extends Files
{
    /**
     * @var FrontendUser
     */
    private $objUser;

    /**
     * @var FilesModel
     */
    private $objFile;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var boolean
     */
    private $details = false;

    /**
     * @var array
     */
    private $files;

    /**
     * @var array
     */
    private $errors = array();

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(UrlGeneratorInterface $router, EntityManager $entityManager, FormFactoryInterface $formFactory)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * Creates the File Array
     */
    public function loadFiles(){
        //checks the User Rights to use the filemanager
        if ($this->objUser->assignDir){
            //uses the homeDir if no file is given
            if(!($this->objFile && $this->hasAccess($this->objFile))){
                $this->objFile = FilesModel::findByUuid($this->objUser->homeDir);
            }

            //loads either the list of files or a detail array for a file
            if($this->objFile->type === 'folder' && $this->details === false){
                $this->loadDirectory($this->objFile);
            } else {
                $this->loadDetails($this->objFile);
            }
        } else {
            try {
                throw new \Exception("Sie haben keine Berechtigung");
            } catch (\Exception $e) {
                array_push($this->errors, $e->getMessage());
            }
        }
    }

    public function loadBreadcrumb() {
        $breadcrumb = array();
        //check if a subfolder

        $currentPath = $this->objFile->path;
        $homePath = FilesModel::findByUuid($this->objUser->homeDir)->path;
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

        //adds the home path to the breadcrumb
        $breadcrumb[] = array(
            'name' => 'Home',
            'href'  => $this->router->generate('learningcenter_files')
        );
        //returns the array in reverse
        return array_reverse($breadcrumb);
    }

    public function loadToolbar() {
    }

    /**
    * @param FrontendUser $objUser
    */
    public function setObjUser(FrontendUser $objUser)
    {
        $this->objUser = $objUser;
    }

    /**
     * @return FrontendUser
     */
    public function getObjUser(): FrontendUser
    {
        return $this->objUser;
    }

    /**
     * @param bool $details
     */
    public function setDetails(bool $details)
    {
        $this->details = $details;
    }

    /**
     * @param integer $fid
     */
    public function setObjFile($fid)
    {
        $this->objFile = FilesModel::findById($fid);
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param FilesModel $objFile
     * @throws \Exception
     */
    private function loadDirectory($objFile){
        if (!$this->isFolderEmpty($objFile)){
            $objFiles = FilesModel::findByPid($this->objFile->uuid);
            while($objFiles->next()){
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
            $this->files = $this->sortByTypeAndName($files);
        } else {
            try {
                throw new \Exception("Der Ordner besitzt keine Dateien!");
            } catch (\Exception $e) {
                array_push($this->errors, $e->getMessage());
            }

        }
    }

    /**
     * @param FilesModel $objFile
     */
    private function loadDetails($objFile){

    }

    /**
     * @param FilesModel $objFiles
     * @return bool
     */
    private function hasAccess(FilesModel $objFiles){
        return true;
    }

    /**
     * Check whether a folder is empty or has subfiles/subfolders
     *
     * @param FilesModel $objFolder
     * @return bool
     */
    private function isFolderEmpty(FilesModel $objFolder)
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
    private function sortByTypeAndName($files)
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