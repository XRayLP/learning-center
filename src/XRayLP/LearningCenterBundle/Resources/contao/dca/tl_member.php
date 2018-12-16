<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = '{personal_legend},firstname,lastname,dateOfBirth,gender;{address_legend:hide},company,street,postal,city,state,country;{contact_legend},phone,mobile,fax,email,website,language;{groups_legend},groups,memberType;{login_legend},login;{homedir_legend:hide},assignDir;{account_legend},disable,start,stop';

//subpalettes
$GLOBALS['TL_DCA']['tl_member']['subpalettes']['assignDir'] = 'homeDir,cloudSpace';

//fields
$GLOBALS['TL_DCA']['tl_member']['fields']['cloudSpace'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member']['cloudSpace'],
    'inputType' => 'inputUnit',
    'options'   => array(
        'B'   => 'Byte',
        'KB'  => 'Kilobyte',
        'MB'  => 'Megabyte',
        'GB'  => 'Gigabyte'
    ),
    'eval'      => array('rgxp'=>'digit', 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['groups1'] = array(
    'sql'       => "varchar(255) NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['permissions'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member']['permissions'],
    'search'    => true,
    'inputType' => 'checkbox',
    'options'   => array(
        '1' => &$GLOBALS['TL_LANG']['tl_member']['permissions']['options']['filemanager'],
        '11' => &$GLOBALS['TL_LANG']['tl_member']['permissions']['options']['filemanager_share_projects'],
        '12' => &$GLOBALS['TL_LANG']['tl_member']['permissions']['options']['filemanager_share_catalog'],
        '2' => &$GLOBALS['TL_LANG']['tl_member']['permissions']['options']['catalog'],
        '3' => &$GLOBALS['TL_LANG']['tl_member']['permissions']['options']['projects'],
        '31' => &$GLOBALS['TL_LANG']['tl_member']['permissions']['options']['projects_create'],
        '32' => &$GLOBALS['TL_LANG']['tl_member']['permissions']['options']['projects_create_advanced'],
    ),
    'eval'      => array('mandatory'=>true, 'multiple'=>true, 'tl_class'=>'w50'),
    'sql'       => "blob NULL"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['memberType'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member']['memberType'],
    'search'    => true,
    'inputType' => 'select',
    'options'   => array(
        'ROLE_STUDENT' => &$GLOBALS['TL_LANG']['tl_member']['memberType']['options']['student'],
        'ROLE_TEACHER' => &$GLOBALS['TL_LANG']['tl_member']['memberType']['options']['teacher'],
        'ROLE_PLANNER' => &$GLOBALS['TL_LANG']['tl_member']['memberType']['options']['planner'],
        'ROLE_ADMIN' => &$GLOBALS['TL_LANG']['tl_member']['memberType']['options']['admin'],
    ),
    'eval'      => array('mandatory'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

//fields
$GLOBALS['TL_DCA']['tl_member']['fields']['avatar'] = array(
    'sql'       => "char(1) NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_member']['fields']['grade'] = array(
    'sql'       => "int(10) NULL"
);
//nullable fields
$GLOBALS['TL_DCA']['tl_member']['fields']['tstamp']['sql'] = "int(10) unsigned NULL default '0'";
$GLOBALS['TL_DCA']['tl_member']['fields']['firstname']['sql'] = "varchar(255) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['lastname']['sql'] = "varchar(255) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['dateOfBirth']['sql'] = "varchar(11) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['gender']['sql'] = "varchar(32) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['company']['sql'] = "varchar(255) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['street']['sql'] = "varchar(255) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['postal']['sql'] = "varchar(32) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['city']['sql'] = "varchar(255) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['state']['sql'] = "varchar(64) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['country']['sql'] = "varchar(2) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['phone']['sql'] = "varchar(64) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['mobile']['sql'] = "varchar(64) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['fax']['sql'] = "varchar(64) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['email']['sql'] = "varchar(255) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['website']['sql'] = "varchar(255) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['language']['sql'] = "varchar(5) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['groups']['sql'] = "blob NULL";
$GLOBALS['TL_DCA']['tl_member']['fields']['login']['sql'] = "char(1) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['username']['sql'] = 'varchar(64) BINARY NULL';
$GLOBALS['TL_DCA']['tl_member']['fields']['password']['sql'] = "varchar(255) NOT NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['assignDir']['sql'] = "char(1) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['homeDir']['sql'] = "binary(16) NULL";
$GLOBALS['TL_DCA']['tl_member']['fields']['disable']['sql'] = "char(1) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['start']['sql'] = "varchar(10) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['stop']['sql'] = "varchar(10) NULL default ''";
$GLOBALS['TL_DCA']['tl_member']['fields']['dateAdded']['sql'] = "int(10) unsigned NULL default '0'";
$GLOBALS['TL_DCA']['tl_member']['fields']['lastLogin']['sql'] = "int(10) unsigned NULL default '0'";
$GLOBALS['TL_DCA']['tl_member']['fields']['currentLogin']['sql'] = "int(10) unsigned NULL default '0'";
$GLOBALS['TL_DCA']['tl_member']['fields']['loginCount']['sql'] = "smallint(5) unsigned NULL default '3'";
$GLOBALS['TL_DCA']['tl_member']['fields']['locked']['sql'] = "int(10) unsigned NULL default '0'";
$GLOBALS['TL_DCA']['tl_member']['fields']['session']['sql'] = "blob NULL";
$GLOBALS['TL_DCA']['tl_member']['fields']['createdOn']['sql'] = "int(10) unsigned NULL default '0'";
$GLOBALS['TL_DCA']['tl_member']['fields']['createdOn']['sql'] = "varchar(32) NULL default ''";

