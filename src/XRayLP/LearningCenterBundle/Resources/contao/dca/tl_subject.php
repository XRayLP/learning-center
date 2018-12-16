<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_subject'] = array(
    //Config
    'config' => array(
        'dataContainer' => 'Table',
        'enableVersioning' => true
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
                'label'               => &$GLOBALS['TL_LANG']['tl_subject']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_subject']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_subject']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_subject']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )

    ),

    'palettes' => array(
        'default'   => '{main_legend},name,leader_id,group_id'
    ),
    //Fields
    'fields' => array(
        'name' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_subject']['name'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),
        'leader_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_subject']['leader'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.member', 'getAllMembers'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),
        'group_id' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_subject']['group'],
            'search'    => true,
            'inputType' => 'select',
            'options_callback' => array('learningcenter.utils.member', 'getAllGroups'),
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
        ),

    ),
);