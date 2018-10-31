<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

//Backend Modules
/*$GLOBALS['BE_MOD']['learningcenter']['projects'] = array(
    'tables' => array('tl_projects')
);
/*$GLOBALS['BE_MOD']['learningcenter']['timetable'] = array(
    'tables' => array('tl_timetable')
);*/
$GLOBALS['BE_MOD']['learningcenter'] = array(
    'subjects'      => array(
        'tables' => array('tl_subject')
    ),
    'configuration' => array(
        'tables' => array('tl_lc_configuration')
    ),
);