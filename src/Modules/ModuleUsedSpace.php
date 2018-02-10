<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Modules;


use Contao\Module;
use XRayLP\LearningCenterBundle\Classes\FileManager;

class ModuleUsedSpace extends Module
{
    protected $strTemplate = 'mod_usedspace';

    public function generate()
    {
        $this->import('FrontendUser', 'User');

        return parent::generate();
    }
    protected function compile()
    {
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
        $usedSpace = FileManager::getUsedDirSpace($this->User->homeDir);
        $usedSpaceDegree = round(($usedSpace/$maxSpace)*360);
        $usedSpacePercent = round(($usedSpace/$maxSpace)*100);

        //Templates
        $this->Template->usedSpace = round($usedSpace/(1024*1024), 2);
        $this->Template->maxSpace = round($maxSpace/(1024*1024), 2);
        $this->Template->usedSpacePercent = $usedSpacePercent;
        $this->Template->usedSpaceDegree = $usedSpaceDegree;
    }
}