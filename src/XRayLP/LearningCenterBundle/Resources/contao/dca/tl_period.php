<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_period'] = array(
    //Config
    'config' => array(
        'dataContainer' => 'Table',
        'ptable' => 'tl_timetable',
        'enableVersioning' => true
    ),

    //List
    'list' => array(

        'sorting' => array(
            'mode'          => 1,
            'fields'        => array('subject_id'),
            'flag'          => 1,
            'panelLayout'   => 'filter;search,limit'
        ),
        'label' => array(
            'fields' => array('gradeLevel_id', 'suffix'),
            'format' => '',
            'label_callback' => array('learningcenter.utils.grade', 'getCourseLabel')
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_period']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_period']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_period']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_period']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )

    ),

    'palettes' => array(
        'default'   => '{main_legend},subject_id,gradeLevel_id,suffix,teacher_id,group_id'
    ),
    //Fields
    'fields' => array(
        'subject_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_period']['subject'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.grade', 'getAllSubjects'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

        'gradeLevel_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_period']['gradeLevel'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.grade', 'getAllGradeLevels'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

        'suffix' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_period']['suffix'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

        'teacher_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_period']['teacher'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.member', 'getAllTeachers'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),
        'group_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_period']['group'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.member', 'getAllGroups'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

    ),
);