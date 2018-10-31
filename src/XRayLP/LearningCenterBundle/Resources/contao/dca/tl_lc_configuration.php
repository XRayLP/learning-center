<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_lc_configuration'] = array(

    //Config
    'config' => array(
        'dataContainer' => 'File',
        'closed'        => true
    ),
    	// Palettes
	'palettes' => array(
	    '__selector__'      => array('activate'),
        'default'         => '{activate_legend},activate;',
    ),

	'subpalettes' => array(
	    'activate' => 'name,logo;'
    ),

	// Fields
	'fields' => array(

        'activate' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lc_configuration']['activate'],
            'inputType'               => 'checkbox',
            'eval'                    => array('tl_class'=>'w100', 'submitOnChange'=>true)
        ),
        'name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lc_configuration']['name'],
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'tl_class'=>'w50')
        ),

        'logo' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lc_configuration']['logo'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'filesOnly'=>true, 'tl_class'=>'clr', 'extensions'=>$GLOBALS['TL_CONFIG']['validImageTypes']),
        )
    )

);