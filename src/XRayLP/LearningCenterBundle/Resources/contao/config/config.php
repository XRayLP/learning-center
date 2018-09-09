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
    'callback' => 'App\XRayLP\LearningCenterBundle\Modules\ModuleLCConfiguration'
);


//Frontend Modules
$GLOBALS['FE_MOD']['user']['userlist'] = 'App\XRayLP\LearningCenterBundle\Modules\ModuleUserlist';
$GLOBALS['FE_MOD']['projects']['projects'] = 'App\XRayLP\LearningCenterBundle\Modules\ModuleProjects';
$GLOBALS['FE_MOD']['files']['filemanager'] = 'App\XRayLP\LearningCenterBundle\Modules\ModuleFrontendFileManager';
$GLOBALS['FE_MOD']['files']['catalog'] = 'App\XRayLP\LearningCenterBundle\Modules\ModuleCatalog';
$GLOBALS['FE_MOD']['files']['usedSpace'] = 'App\XRayLP\LearningCenterBundle\Modules\ModuleUsedSpace';

//Formulare
$GLOBALS['TL_FFL']['upload'] = 'App\XRayLP\LearningCenterBundle\Forms\FormFileUpload';
$GLOBALS['TL_FFL']['folder'] = 'App\XRayLP\LearningCenterBundle\Forms\FormCreateFolder';
$GLOBALS['TL_FFL']['delete'] = 'App\XRayLP\LearningCenterBundle\Forms\FormFilesDelete';
$GLOBALS['TL_FFL']['download'] = 'App\XRayLP\LearningCenterBundle\Forms\FormFilesDelete';
$GLOBALS['TL_FFL']['share'] = 'App\XRayLP\LearningCenterBundle\Forms\FormShareFile';

$GLOBALS['TL_MODELS']['tl_projects'] = 'App\XRayLP\LearningCenterBundle\Model\ProjectModel';