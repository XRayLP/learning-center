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
        'default'         => '{activate_legend},activate;{period_legend},period_configuration',
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
        ),
        'period_configuration' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_lc_configuration']['period_configuration'],
            'inputType'               => 'periodWizard',
            'exclude'                 => true,
            /*'eval'                    => array
            (
                'columnFields'  => array
                (
                    'pc_period_number' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_lc_configuration']['pc_period_number'],
                        'inputType'               => 'select',
                        'options'                 => array(1,2,3,4,5,6,7,8),
                        'eval'                    => array('style' => 'width:250px', 'includeBlankOption'=>true, 'chosen'=>true)
                    ),
                    'pc_start_time' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_lc_configuration']['pc_start_time'],
                        'default'                 => time(),
                        'inputType'               => 'text',
                        'eval'                    => array('rgxp'=>'time', 'mandatory'=>true, 'doNotCopy'=>true, 'style' => 'width:250px'),
                        'load_callback' => array
                        (
                            array('tl_calendar_events', 'loadTime')
                        ),
                    ),
                    'pc_stop_time' => array
                    (
                        'label'                   => &$GLOBALS['TL_LANG']['tl_lc_configuration']['pc_stop_time'],
                        'default'                 => time(),
                        'inputType'               => 'text',
                        'eval'                    => array('rgxp'=>'time', 'mandatory'=>true, 'doNotCopy'=>true, 'style' => 'width:250px'),
                    ),
                ),
            ),*/
        ),
    )

);