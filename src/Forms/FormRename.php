<?php
/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 07.02.2018
 * Time: 18:31
 */

namespace XRayLP\LearningCenterBundle\Forms;

use Contao\Widget;

class FormRename extends Widget
{

    /**
     * Create the folder if a valid name were chosen.
     */
    public function validate()
    {
        //get the chosen files and groups
        if ($this->getPost('files-rename') !== null) {
            $fileID = $this->getPost('files-rename');
        }

        $value = $this->getPost('rename');

        $objFile = FilesModel::findById($fileID);
        $objFile->name = $value.$objFile->extension;
    }

    /**
     * Generate the widget and return it as string
     *
     * @return string The widget markup
     */
    public function generate()
    {
        return sprintf('<input type="text" name="rename" id="ctrl_%s" class="rename%s"%s%s',
            //$this->strName,
            $this->strId,
            (($this->strClass != '') ? ' ' . $this->strClass : ''),
            $this->getAttributes(),
            $this->strTagEnding);
    }
}