<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

//Backend Modules
$GLOBALS['BE_MOD']['learningcenter']['projects'] = array(
    'tables' => array('tl_projects')
);
/*$GLOBALS['BE_MOD']['learningcenter']['timetable'] = array(
    'tables' => array('tl_timetable')
);*/
$GLOBALS['BE_MOD']['learningcenter']['configuration'] = array(
    'callback' => 'XRayLP\LearningCenterBundle\Modules\ModuleLCConfiguration'
);


//Frontend Modules
$GLOBALS['FE_MOD']['user']['userlist'] = 'XRayLP\LearningCenterBundle\Modules\ModuleUserlist';
$GLOBALS['FE_MOD']['projects']['projects'] = 'XRayLP\LearningCenterBundle\Modules\ModuleProjects';
$GLOBALS['FE_MOD']['files']['filemanager'] = 'XRayLP\LearningCenterBundle\Modules\ModuleFrontendFileManager';
$GLOBALS['FE_MOD']['files']['catalog'] = 'XRayLP\LearningCenterBundle\Modules\ModuleCatalog';
$GLOBALS['FE_MOD']['files']['usedSpace'] = 'XRayLP\LearningCenterBundle\Modules\ModuleUsedSpace';

//Formulare
$GLOBALS['TL_FFL']['upload'] = 'XRayLP\LearningCenterBundle\Forms\FormFileUpload';
$GLOBALS['TL_FFL']['folder'] = 'XRayLP\LearningCenterBundle\Forms\FormCreateFolder';
$GLOBALS['TL_FFL']['delete'] = 'XRayLP\LearningCenterBundle\Forms\FormFilesDelete';
$GLOBALS['TL_FFL']['download'] = 'XRayLP\LearningCenterBundle\Forms\FormFilesDelete';
$GLOBALS['TL_FFL']['share'] = 'XRayLP\LearningCenterBundle\Forms\FormShareFile';

$GLOBALS['TL_MODELS']['tl_projects'] = 'XRayLP\LearningCenterBundle\Model\ProjectModel';