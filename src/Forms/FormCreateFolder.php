<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Forms;

use XRayLP\LearningCenterBundle\Classes\FileManager;

/**
 * Class FormCreateFolder
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class FormCreateFolder extends \Widget
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'form_create_folder';
    
    /**
     * The CSS class prefix
     *
     * @var string
     */
    protected $strPrefix = 'widget';

    /**
     * Generate the widget and return it as string
     *
     * @return string The widget markup
     */
    public function generate()
    {
        return sprintf('<input type="text" name="createFolder" id="ctrl_%s" class="folder%s"%s%s',
            //$this->strName,
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            $this->getAttributes(),
            $this->strTagEnding);
    }

    /**
     * Create the folder if a valid name were chosen.
     */
    public function validate()
    {
        $fid = $_GET['fid'];
        
        $value = $this->getPost('createfolder');
        
        $this->import('FrontendUser', 'User');

        //use the path of the parent folder
        if(isset($fid)) {
            $folderPath = \FilesModel::findById($fid)->path;
        } else {
            $folderPath = \FilesModel::findByPk($this->User->homeDir)->path;
        }

        //create new folder path
        $newFolderPath = $folderPath.'/'.$value;

        //create the folder
        $objFolder = new \Folder($newFolderPath);
        FileManager::createFromFolder($objFolder->path);
    }

}

