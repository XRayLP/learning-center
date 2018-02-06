<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_projects'] = array(
    
    //Config
    'config' => array(
        'dataContainer' => 'Table',
        'switchToEdit' => true,
        'enableVersioning' => true,
        'sql' => array(
            'keys' => array(
                'id' => 'primary'
            )
        )
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
        'default' => '{main_legend},name,alias,description,members'  
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
            'save_callback' => array(array('tl_projects', 'generateAlias')),
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
        'members' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_projects']['members'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'foreignKey'              => 'tl_member.username',
            'eval'                    => array('mandatory'=>true, 'multiple'=>true),
            'sql'                     => "blob NULL",
            'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
        )
    )   
);

class tl_projects extends Backend
{
    /**
     * Generate an alias for an project.
     *
     * @param $varValue
     * @param $dc
     * @return string
     * @throws Exception
     */
    public function generateAlias($varValue, $dc)
    {
        $autoAlias = false;
        
        // generate an alias if it doesn't exist
        if ($varValue == '') {
            $autoAlias = true;
            $varValue = StringUtil::generateAlias($dc->activeRecord->title);
        }
        
        // table, for which an alias should be created
        $table = Input::get('table') ? Input::get('table') : 'tl_projects';
        
        $objAlias = $this->Database->prepare("SELECT id FROM " . $table . " WHERE alias=?")->execute($varValue);
        
        // check whether the alias already exists
        if ($objAlias->numRows > 1 && !$autoAlias) {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }
        
        // if alias exist, add an id
        if ($objAlias->numRows && $autoAlias) {
            $varValue .= '-' . $dc->id;
        }
        
        return $varValue;
    }
}