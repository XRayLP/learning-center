<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

$GLOBALS['TL_DCA']['tl_member_group']['palettes']['default'] = '{title_legend},name, groupType;{redirect_legend:hide},redirect;{disable_legend},disable,start,stop';
$GLOBALS['TL_DCA']['tl_member_group']['fields']['groupType'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_member_group']['groupType'],
    'search'    => true,
    'inputType' => 'select',
    'options'   => array(
        '0' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['others'],
        '1' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['student'],
        '2' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['teacher'],
        '3' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['class'],
        '4' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['course'],
        '5' => &$GLOBALS['TL_LANG']['tl_member_group']['groupType']['options']['project'],
    ),
    'sql'       => "int(10) Not NULL default '0'"
);