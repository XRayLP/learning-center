<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_timetable'] = array(

    //Config
    'config' => array(
        'dataContainer'     => 'Table',
        'ctable' => array('tl_period'),
        'switchToEdit'      => true,
        'enableVersioning'  => true,
    ),

    //List
    'list' => array(

        'sorting' => array(
            'mode'          => 1,
            'fields'        => array('name'),
            'flag'          => 1,
            'panelLayout'   => 'filter;search,limit'
        ),
        'label' => array(
            'fields' => array('name'),
            'format' => '%s'
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade_level']['edit'],
                'href'                => 'table=tl_period',
                'icon'                => 'edit.svg'
            ),
            'editheader' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade_level']['editheader'],
                'href'                => 'act=edit',
                'icon'                => 'header.svg',
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetable']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetable']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_timetable']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )

    ),

    'palettes' => array(
        'default'   => '{main_legend},name,schoolDays,lessons'
    ),
    //Fields
    'fields' => array(
        'name' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_timetable']['name'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w100'),
        ),

        'schoolDays' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_timetable']['schoolDays'],
            'search'    => true,
            'inputType' => 'checkbox',
            'options' => array('0' => 'mon', '1' => 'tue', '2' => 'wed', '3' => 'thu', '4' => 'fri', '5' => 'sat', '6' => 'sun'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w100', 'multiple'=>true),
        ),
        'lessons' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_timetable']['lessons'],
            'inputType' => 'periodWizard',
            'exclude' => true,
        ),

    ),
);
