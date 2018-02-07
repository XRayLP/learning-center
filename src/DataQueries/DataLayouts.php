<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


class DataLayouts
{
    public static function getDataLayout()
    {
        return $layouts = array(
            '1' => array(
                'id'                    => 1,
                'pid'                   => 1,
                'tstamp'                => time(),
                'name'                  => 'Extern',
                'rows'                  => '3rw',
                'footerHeight'          => 'a:2:{s:4:"unit";s:0:"";s:5:"value";s:0:"";}',
                'cols'                  => '1cl',
                'widthLeft'             => 'a:2:{s:4:"unit";s:0:"";s:5:"value";s:0:"";}',
                'sections'              => 'a:1:{i:0;a:4:{s:5:"title";s:0:"";s:2:"id";s:0:"";s:8:"template";s:13:"block_section";s:8:"position";s:3:"top";}}',
                'modules'               => 'a:1:{i:0;a:3:{s:3:"mod";s:1:"0";s:3:"col";s:4:"main";s:6:"enable";s:1:"1";}}',
                'template'              => 'fe_bootstrap_lc',
                'doctype'               => 'html5',
                'viewport'              => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no',
                'addJQuery'             => 1,
                'jSource'               => 'j_local',
                'align'                 => 'center',
                'layoutType'            => 'bootstrap',
                'bs_containerClass'     => 'container extern',
                'bs_containerElement'   => 'container'
            ),
            '2' => array(
                'id'                    => 2,
                'pid'                   => 1,
                'tstamp'                => time(),
                'name'                  => 'Intern',
                'rows'                  => '3rw',
                'headerHeight'          => 'a:2:{s:4:"unit";s:0:"";s:5:"value";s:0:"";}',
                'footerHeight'          => 'a:2:{s:4:"unit";s:0:"";s:5:"value";s:0:"";}',
                'cols'                  => '2cll',
                'widthLeft'             => 'a:2:{s:4:"unit";s:1:"%";s:5:"value";s:2:"20";}',
                'sections'              => 'a:1:{i:0;a:4:{s:5:"title";s:0:"";s:2:"id";s:0:"";s:8:"template";s:13:"block_section";s:8:"position";s:3:"top";}}',
                'modules'               => 'a:3:{i:0;a:3:{s:3:"mod";s:1:"5";s:3:"col";s:6:"header";s:6:"enable";s:1:"1";}i:1;a:3:{s:3:"mod";s:1:"3";s:3:"col";s:4:"left";s:6:"enable";s:1:"1";}i:2;a:3:{s:3:"mod";s:1:"0";s:3:"col";s:4:"main";s:6:"enable";s:1:"1";}}',
                'template'              => 'fe_bootstrap_lc',
                'doctype'               => 'html5',
                'viewport'              => 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no',
                'addJQuery'             => 1,
                'jSource'               => 'j_local',
                'align'                 => 'center',
                'layoutType'            => 'bootstrap',
                'bs_containerClass'     => 'container-fluid intern',
                'bs_containerElement'   => 'container',
                'bs_rightClass'         => 'col-3',
                'bs_leftClass'          => 'col-12 col-md-3 col-lg-2 d-none d-md-block',
                'bs_mainClass'          => 'col-12 col-md-9 col-lg-10'
            ),
        );
    }
}