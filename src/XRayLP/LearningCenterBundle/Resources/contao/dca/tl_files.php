<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

//fields
$GLOBALS['TL_DCA']['tl_files']['fields']['owner'] = array(
    'sql' => "int(10) NULL"
);
$GLOBALS['TL_DCA']['tl_files']['fields']['shared'] = array(
    'sql' => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_files']['fields']['shared_groups'] = array(
    'sql' => "blob NULL"
);
$GLOBALS['TL_DCA']['tl_files']['fields']['shared_tstamp'] = array(
    'sql' => "int(10) NULL"
);