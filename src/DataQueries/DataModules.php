<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


class DataModules
{
    public static function getDataModules()
    {
        return $modules = array(
            '1' => array(
                'id'            => 1,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Loginformular',
                'type'          => 'login',
                'customTpl'     => 'mod_login_lc',
                'autologin'     => 1,
                'jumpTo'        => 5,
            ),
            '2' => array(
                'id'            => 2,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Profildatenformular',
                'type'          => 'personalData',
                'jumpTo'        => 5,
                'editable'      => 'a:13:{i:0;s:9:"firstname";i:1;s:8:"lastname";i:2;s:11:"dateOfBirth";i:3;s:6:"gender";i:4;s:6:"street";i:5;s:6:"postal";i:6;s:4:"city";i:7;s:5:"phone";i:8;s:6:"mobile";i:9;s:3:"fax";i:10;s:5:"email";i:11;s:7:"website";i:12;s:8:"password";}',
                'memberTpl'     => 'member_grouped_lc'
            ),
            '3' => array(
                'id'            => 3,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Seitenleistennavigation',
                'type'          => 'navigation',
                'levelOffset'   => 1,
                'showLevel'     => 1,
                'hardLimit'     => 1,
                'showProtected' => 1,
                'navigationTpl' => 'nav_default_lc'
            ),
            '4' => array(
                'id'            => 4,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Abmelden',
                'type'          => 'logout',
                'jumpTo'        => 4,
            ),
            '5' => array(
                'id'                        => 5,
                'pid'                       => 1,
                'tstamp'                    => time(),
                'name'                      => 'Navigationsleiste',
                'type'                      => 'bs_navbar',
                'customTpl'                 => 'mod_bs_navbar_lc',
                'bs_isResponsive'           => 1,
                'bs_toggleableSize'         => 'md',
                'bs_navbarBrandTemplate'    => 'navbar_brand',
                'bs_navbarModules'          => 'a:1:{i:0;a:3:{s:6:"module";s:1:"6";s:8:"cssClass";s:0:"";s:8:"inactive";s:0:"";}}',
                'bs_addBrand'               => 1,
            ),
            '6' => array(
                'id'            => 6,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Kopfnavigation',
                'type'          => 'customnav',
                'showProtected' => 1,
                'navigationTpl' => 'nav_navbar',
                'customTpl'     => 'mod_customnav_lc',
                'pages'         => 'a:1:{i:0;i:11;}',
                'orderPages'    => 'a:1:{i:0;s:1:"11";}',
                'customLabel'   => 'Abmelden'
            ),
            '7' => array(
                'id'            => 7,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Klassenliste',
                'type'          => 'userlist',
            ),
            '8' => array(
                'id'            => 8,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Projektliste',
                'type'          => 'projectlist',
            ),
            '9' => array(
                'id'            => 9,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Dateimanager',
                'type'          => 'filemanager',
            ),
            '10' => array(
                'id'            => 10,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Katalog',
                'type'          => 'catalog',
            ),
            '11' => array(
                'id'            => 11,
                'pid'           => 1,
                'tstamp'        => time(),
                'name'          => 'Belegter Speicherplatz',
                'type'          => 'usedSpace',
            ),
        );
    }
}
