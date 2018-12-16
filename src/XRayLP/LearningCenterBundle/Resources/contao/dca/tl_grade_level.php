<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_grade_level'] = array(
    //Config
    'config' => array(
        'dataContainer' => 'Table',
        'ctable' => array('tl_grade'),
        'enableVersioning' => true
    ),

    //List
    'list' => array(

        'label' => array(
            'fields' => array('gradeNumber'),
            'format' => '%s. Klasse'
        ),
        'operations' => array
        (

            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade_level']['edit'],
                'href'                => 'table=tl_grade',
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
                'label'               => &$GLOBALS['TL_LANG']['tl_grade_level']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade_level']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade_level']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )

    ),

    'palettes' => array(
        'default'   => '{main_legend},gradeNumber,gradeSuffix'
    ),
    //Fields
    'fields' => array(
        'gradeNumber' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_grade_level']['gradeNumber'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),
        'gradeSuffix' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_grade_level']['gradeSuffix'],
            'search'    => true,
            'inputType' => 'select',
            'options'   => array('integer' => &$GLOBALS['TL_LANG']['tl_grade_level']['gradeSuffix']['options']['integer'], 'string' => &$GLOBALS['TL_LANG']['tl_grade_level']['gradeSuffix']['options']['string']),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

    ),
);