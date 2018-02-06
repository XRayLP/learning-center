<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Forms;

use XRayLP\LearningCenterBundle\Classes\FileManager;

/**
 * Class FormFilesCheckbox
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class FormFilesDelete extends \Widget
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'form_hidden';
    
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
        return sprintf('<input type="hidden" name="%s" value="%s"%s',
            $this->strName,
            \StringUtil::specialchars($this->varValue),
            $this->strTagEnding);
    }

    /**
     * Delete the files/folders by id
     */
    public function validate()
    {
        $this->import('FrontendUser', 'User');
        $files = array();

        //get the file id if it is set
        if ($this->getPost('files-delete') !== null) {
            $fid = $this->getPost('files-delete');

            $objFile = \FilesModel::findById($fid);

            //for folders: add all subfiles and subfiles to files array
            if ($objFile->type == 'folder')
            {
                //reverse array to delete the files in the right order
                $files = array_reverse(FileManager::getAllSubfilesAndFolders($objFile->uuid));
            } else {
                $files[] = $objFile;
            }

            //delete each file/folder in the filesystem and in the database
            foreach ($files as $objFile)
            {
                if($objFile->type == 'folder') {
                    rmdir($objFile->path);
                } else {
                    unlink($objFile->path);
                }
                $objFile->delete();
            }

        }
    }

}

