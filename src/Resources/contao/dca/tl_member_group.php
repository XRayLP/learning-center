<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_member_group']['palettes']['__selector__'][1] = 'groupType';
$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] = '{title_legend},name, groupType;{redirect_legend:hide},redirect;{disable_legend},disable,start,stop';
$GLOBALS['TL_DCA']['tl_member_group']['palettes']['2'] = '{title_legend},name, groupType;{class_legend},classNumber;{redirect_legend:hide},redirect;{disable_legend},disable,start,stop';
$GLOBALS['TL_DCA']['tl_member_group']['palettes']['3'] = '{title_legend},name, groupType;{course_legend},courseName,courseNumber;{redirect_legend:hide},redirect;{disable_legend},disable,start,stop';
$GLOBALS['TL_DCA']['tl_member_group']['palettes']['4'] = '{title_legend},name, groupType;{project_legend},projectName,projectDescription;{redirect_legend:hide},redirect;{disable_legend},disable,start,stop';
$GLOBALS['TL_DCA']['tl_member_group']['fields']['groupType'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member_group']['groupType'],
    'search'    => true,
    'inputType' => 'select',
    'options'   => array(
        '1' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['others'],
        '2' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['class'],
        '3' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['course'],
        '4' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['project'],
    ),
    'eval'      => array('mandatory'=>true, 'submitOnChange'=>true, 'tl_class'=>'w50'),
    'sql'       => "int(10) Not NULL default '1'"
);

$GLOBALS['TL_DCA']['tl_member_group']['fields']['classNumber'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member_group']['classNumber'],
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('mandatory'=>true, 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member_group']['fields']['courseName'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member_group']['courseName'],
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('mandatory'=>true, 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_member_group']['fields']['courseNumber'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member_group']['courseNumber'],
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('mandatory'=>true, 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member_group']['fields']['projectName'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member_group']['projectName'],
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('mandatory'=>true, 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_member_group']['fields']['projectDescription'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member_group']['projectDescription'],
    'search'    => true,
    'inputType' => 'text',
    'eval'      => array('mandatory'=>true, 'tl_class'=>'w50'),
    'sql'       => "varchar(255) NOT NULL default ''"
);


