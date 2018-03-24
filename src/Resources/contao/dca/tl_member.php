<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

//subpalettes
$GLOBALS['TL_DCA']['tl_member']['subpalettes']['assignDir'] = 'homeDir,cloudSpace';

//fields
$GLOBALS['TL_DCA']['tl_member']['fields']['cloudSpace'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member']['cloudSpace'],
    'inputType' => 'inputUnit',
    'options'   => array(
        'B'   => 'Byte',
        'KB'  => 'Kilobyte',
        'MB'  => 'Megabyte',
        'GB'  => 'Gigabyte'
    ),
    'eval'      => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

//fields
$GLOBALS['TL_DCA']['tl_member']['fields']['avatar'] = array(
    'sql'       => "char(1) NOT NULL default '0'"
);