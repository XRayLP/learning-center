<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Modules;

use Contao\FilesModel;
use Contao\Module;
use Contao\PageModel;
use XRayLP\LearningCenterBundle\Classes\ArrayHelper;
use XRayLP\LearningCenterBundle\Classes\FileManager;
use XRayLP\LearningCenterBundle\Classes\UserHelper;

/**
 * Front end module "filemanager".
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class ModuleFrontendFileManager extends Module 
{
    /**
     * Files object
     * @var Model\Collection|FilesModel
     */
    protected $objFiles;

    /**
     * Files object
     * @var Model\Collection|FilesModel
     */
    protected $objFilesTest;

    /**
     * Files object
     * @var Model\Collection|FilesModel
     */
    protected $objDirectory;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = "mod_files";

    /**
     * Return if there are no files
     *
     * @return string
     */
    public function generate()
    {
        $this->import('FrontendUser', 'User');
        
        $fid = $_GET['fid'];

        //test whether the user is logged in and has home dir
        if ($this->User->assignDir && $this->User->homeDir)
        {
            //Subfolders
            if(isset($fid)) {
                $singleSRC = \FilesModel::findById($fid)->uuid;
            } else {
                $singleSRC = $this->User->homeDir;
            }

            $this->multiSRC = array($singleSRC);


        }
        
        // Get the file entries from the database
        $this->objFilesTest = \FilesModel::findMultipleByUuids($this->multiSRC)->uuid;
        $this->objFiles = \FilesModel::findMultipleByUuids($this->multiSRC);
        $this->objDirectory = \FilesModel::findByUuid($singleSRC);

        if ($this->objFiles === null)
        {
            return '';
        }
        
        
        $file = \Input::get('file', true);
        
        // Send the file to the browser and do not send a 404 header (see #4632)
        if ($file != '' && !preg_match('/^meta(_[a-z]{2})?\.txt$/', basename($file)))
        {
            while ($this->objFiles->next())
            {
                if ($file == $this->objFiles->path || \dirname($file) == $this->objFiles->path)
                {
                    \Controller::sendFileToBrowser($file);
                }
            }
            
            $this->objFiles->reset();
        }
        
        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {

        /** @var PageModel $objPage */
        global $objPage;

        /*
         * Files
         */
        $files = array();

        $objFiles = $this->objFiles;

        $allowedDownload = \StringUtil::trimsplit(',', strtolower(\Config::get('allowedDownload')));
        
        //Checks if the folder contains files
        if (\FilesModel::findByPid($this->objFilesTest) !== null){
            // Get all files
            while ($objFiles->next())
            {
                
                // Continue if the files has been processed or does not exist
                if (isset($files[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path))
                {
                    continue;
                }

                //find files by a parent folder uuid
                $objFiles = \FilesModel::findByPid($objFiles->uuid);
                    
                if ($objFiles === null)
                {
                    continue;
                }
                    
                while ($objFiles->next())
                {
                    //folders
                    if ($objFiles->type == 'folder')
                    {    
                        $objFolder = new \Folder($objFiles->path);

                        //url for subfolders
                        $urlRequest = preg_replace('/([?&])'."fid".'=[^&]+(&|$)/','',\Environment::get('request'));
                        $strHref =  $urlRequest . '?fid=' . $objFiles->id;

                        //adds folder to array
                        $files[$objFolder->path] = array
                        (
                            'id'        => $objFiles->id,
                            'uuid'      => $objFiles->uuid,
                            'type'      => $objFiles->type,
                            'time'      => date("d. M Y", $objFolder->mtime),
                            'path'      => $objFolder->path,
                            'name'      => $objFolder->filename,
                            'href'      => $strHref,
                            'shared'    => $objFiles->shared,
                            'shared_group' => $objFiles->shared_groups
                        );
                    }
                    //files
                    else 
                    {
                        $objFile = new \File($objFiles->path);

                        //check whether the file extensions are allowed
                        if (!\in_array($objFile->extension, $allowedDownload) || preg_match('/^meta(_[a-z]{2})?\.txt$/', $objFile->basename))
                        {
                            continue;
                        }

                        
                        $strHref = \Environment::get('request');
                        
                        // Remove an existing file parameter
                        if (preg_match('/(&(amp;)?|\?)file=/', $strHref))
                        {
                            $strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
                        }

                        //url for file download
                        $strHref .= (strpos($strHref, '?') !== false ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFiles->path);
                        
                        //add file to array
                        $files[$objFiles->path] = array
                        (
                            'id'        => $objFiles->id,
                            'uuid'      => $objFiles->uuid,
                            'time'      => date("d. M Y", $objFile->mtime),
                            'type'      => $objFiles->type,
                            'name'      => $objFile->basename,
                            'title'     => \StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['download'], $objFile->basename)),
                            'href'      => $strHref,
                            'filesize'  => $this->getReadableSize($objFile->filesize, 1),
                            'icon'      => \Image::getPath($objFile->icon),
                            'extension' => $objFile->extension,
                            'path'      => $objFile->dirname,
                            'shared'    => $objFiles->shared,
                            'shared_group' => $objFiles->shared_groups
                        );
                    }
                }
            }
        }
        //sorts the files by type and name
        $files = ArrayHelper::array_msort($files, array('type'=>SORT_DESC, 'name'=>SORT_ASC));
            
        /*
         * Breadcrumb
         */
        $breadcrumb = array();

        $objDirectory = $this->objDirectory;
        $objHomeDirectory = \FilesModel::findByUuid($this->User->homeDir);
        
        //delete the folder get variable
        $urlHome = preg_replace('/([?&])'."fid".'=[^&]+(&|$)/','',\Environment::get('request'));
        
        //adds the home folder to the breadcrumb
        $breadcrumb[$objHomeDirectory->path] = array(
            'id' => $objHomeDirectory->id,
            'uuid' => $objHomeDirectory->uuid,
            'filePath' => $objHomeDirectory->path,
            'path' => $urlHome,
            'name' => 'Home',
        );

        //gets the other paths from current folder (stops at the home folder)
        $directory = $objDirectory->path;
        while($objHomeDirectory->path != $directory)
        {
            $objDir = \FilesModel::findByPath($directory);

            //url with file id for the directory
            $urlRequest = preg_replace('/([?&])'."fid".'=[^&]+(&|$)/','',\Environment::get('request'));
            $strHref =  $urlRequest . '?fid=' . $objDir->id;

            //adds the current directory to the breadcrumb
            $breadcrumb[$objDir->path] = array(
                'id' => $objDir->id,
                'uuid' => $objDir->uuid,
                'filePath' => $objDir->path,
                'path' => $strHref,
                'name' => $objDir->name,
            );
            
            //cut one part of the string after the last "/" to check the next directory
            $rmLength = strrpos($directory, "/") - strlen($directory);
            $directory = substr($directory, 0, $rmLength);
        }
        //sorts the breadcrumb by length of the keys
        ksort($breadcrumb);

        /*
         * Used Space
         */

        $arrSpace = $this->User->cloudSpace;

        //gets and build the max space for the current user
        switch($arrSpace['unit']){
            case 'B':
                $maxSpace = $arrSpace['value'];
                break;
            case 'KB':
                $maxSpace = $arrSpace['value']*1024;
                break;
            case 'MB':
                $maxSpace = $arrSpace['value']*1024*1024;
                break;
            case 'GB':
                $maxSpace = $arrSpace['value']*1024*1024*1024;
                break;
        }
        //rounded percent of used space
        $usedSpace = round((FileManager::getUsedDirSpace($this->User->homeDir)/$maxSpace)*100);

        /*
         * Student or Teacher
         */
        $isStudent = UserHelper::isStudent($this->User);
        $isTeacher = UserHelper::isTeacher($this->User);
        
        /*
         * Template variables
         */
        $this->Template->files      = $files;
        $this->Template->breadcrumb = $breadcrumb;
        $this->Template->usedSpace  = $usedSpace;
        $this->Template->isStudent  = $isStudent;
        $this->Template->isTeacher  = $isTeacher;
            
    }

}
