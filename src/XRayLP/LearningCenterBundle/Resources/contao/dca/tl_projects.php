<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_projects'] = array(

    //Config
    'config' => array(
        'dataContainer' => App\XRayLP\LearningCenterBundle\Entity\Project::class,
    ),

    //List
    /*'list' => array(
        
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
                'label'               => &$GLOBALS['TL_LANG']['tl_projects']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_projects']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_projects']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_projects']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
        
    ),
    
    // Palettes
    'palettes' => array(
        'default' => '{main_legend},name,alias,description,groupId'
    ),
    
    //Fields
    'fields' => array(
        'id' => array(
            'sql'   => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'    => array(
            'sql'   => "int(10) unsigned NOT NULL default '0'"
        ),
        'name' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_projects']['name'],
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array
        (
            'label' => &$GLOBALS['TL_LANG']['tl_projects']['alias'],
            'inputType' => 'text',
            'eval' => array('rgxp' => 'alias', 'maxlength' => 128, 'tl_class'=>'w50'),
            'save_callback' => array(
                function($varValue, DataContainer $dataContainer)
                {
                    return \System::getContainer()->get('learningcenter.project')->generateAlias($varValue, $dataContainer);
                }
            ),
            'sql' => "varchar(128) COLLATE utf8_bin NOT NULL default ''"
        ),
        'description' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_projects']['description'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('mandatory'=>true, 'rte'=>'tinyMCE', 'helpwizard'=>true, 'tl_class'=>'clr'),
            'explanation'             => 'insertTags',
            'sql'                     => "mediumtext NULL"
        ),
        'groupId' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_projects']['groupId'],
            'exclude'                 => true,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_member_group.name',
            'eval'                    => array('mandatory'=>true),
            'sql'                     => "int(10) NOT NULL default '0'",
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
        )
    )*/
);