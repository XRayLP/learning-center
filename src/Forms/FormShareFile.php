<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Forms;

use Contao\Database;
use Contao\FilesModel;
use XRayLP\LearningCenterBundle\Classes\FileManager;
use XRayLP\LearningCenterBundle\Classes\UserHelper;

/**
 * Class FormShareFile
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class FormShareFile extends \Widget
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'form_share_file';
    
    
    /**
     * The CSS class prefix
     *
     * @var string
     */
    protected $strPrefix = 'widget widget-checkbox';

    /**
     * Validate the input and create the database entry
     */
    public function validate()
    {
        $this->import('FrontendUser', 'User');
        $files = array();

        //get the chosen files and groups
        if ($this->getPost('files-share') !== null) {
            $fileID = $this->getPost('files-share');
        } 
        if ($this->getPost('groups') !== null) {
            $groupID = $this->getPost('groups');
        }

        if(isset($fileID) && isset($groupID)) {
            $groups = serialize($groupID);
            $objFile = FilesModel::findById($fileID);

            //for folders: add all subfiles and subfiles to files array
            if ($objFile->type == 'folder')
            {
                $files = FileManager::getAllSubfilesAndFolders($objFile->uuid);
            } else {
                $files[] = $objFile;
            }

            //add the share groups to each file
            foreach ($files as $objFile)
            {
                Database::getInstance()
                    ->query("UPDATE tl_files SET shared_groups='" . $groups . "' WHERE id='" . $objFile->id . "'");
                Database::getInstance()
                    ->query("UPDATE tl_files SET shared='1' WHERE id='" . $objFile->id . "'");
            }
        }
    }

    /**
     * Generate the options by courses
     *
     * @return array The options array
     */
    protected function getOptions()
    {
        
        $this->import('FrontendUser', 'User');
        
        $arrOptions = array();
        $arrGroups = array();
        $objGroups = UserHelper::getCourses($this->User);

        //build for each course group an array
        foreach ($objGroups as $objGroup)
        {
            $arrGroups[$objGroup->id] = [
                'id' => $objGroup->id,
                'name' => $objGroup->name
            ];
        }

        //build the option array from the group array
        foreach ($arrGroups as $arrOption)
        {
            $arrOptions[] = array_replace
            (
                $arrOption,
                array
                (
                    'type'       => 'option',
                    'name'       => $arrOption['name'],
                    'id'         => $arrOption['id'],
                    'value'      => $arrOption['id'],
                    'label'      => $arrOption['name']
                )
            );
        }
        return $arrOptions;
    }

    /**
     * Generate the widget and return it as string
     *
     * @return string The widget markup
     */
    public function generate()
    {
        //generate the options strings for each course
        foreach ($arrGroups as $i=>$arrOption)
        {
            $strOptions .= sprintf('<option name="%s" id="opt_%s"  value="%s" >%s</option>',
                $arrOption['name'],
                $arrOption['id'],
                $arrOption['value'],
                $arrOption['name']);
        }

        return sprintf('<select name="%s" id="ctrl_%s" class="%s"%s>%s</select>',
            $this->strName,
            $this->strId,
            $this->class,
            $this->getAttributes(),
            $strOptions);
    }

}

