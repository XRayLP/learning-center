<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */


$GLOBALS['TL_DCA']['tl_grade'] = array(
    //Config
    'config' => array(
        'dataContainer' => 'Table',
        'ptable' => 'tl_grade_level',
        'switchToEdit' => true,
        'enableVersioning' => true
    ),

    //List
    'list' => array(

        'sorting' => array(
            'mode'          => 1,
            'fields'        => array('suffix'),
            'flag'          => 1,
            'panelLayout'   => 'filter;search,limit'
        ),
        'label' => array(
            'fields' => array('gradeLevel_id', 'suffix'),
            'format' => 'Klasse %s-%s',
            'label_callback' => array('learningcenter.utils.grade', 'getGradeLabel')
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_grade']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )

    ),

    'palettes' => array(
        'default'   => '{main_legend},suffix,tutor_id,group_id'
    ),
    //Fields
    'fields' => array(
        'suffix' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_grade']['suffix'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

        'tutor_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_grade']['tutor'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.member', 'getAllTeachers'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),
        'group_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_grade']['group'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.member', 'getAllGroups'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

    ),
);