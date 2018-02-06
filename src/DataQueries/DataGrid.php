<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


class DataGrid
{
    public static function getDataGrid()
    {
        return $grids = array(
            '1' => array(
                'id'            => 1,
                'pid'           => 1,
                'tstamp'        => time(),
                'title'         => 'Login',
                'description'   => 'Login',
                'sizes'         => 'a:5:{i:0;s:2:"xs";i:1;s:2:"sm";i:2;s:2:"md";i:3;s:2:"lg";i:4;s:2:"xl";}',
                'xsSize'        => 'a:1:{i:0;a:6:{s:5:"width";s:2:"12";s:6:"offset";s:0:"";s:5:"order";s:0:"";s:5:"align";s:6:"center";s:5:"class";s:0:"";s:5:"reset";s:0:"";}}',
                'smSize'        => 'a:1:{i:0;a:6:{s:5:"width";s:2:"10";s:6:"offset";s:0:"";s:5:"order";s:0:"";s:5:"align";s:6:"center";s:5:"class";s:0:"";s:5:"reset";s:0:"";}}',
                'mdSize'        => 'a:1:{i:0;a:6:{s:5:"width";s:1:"8";s:6:"offset";s:0:"";s:5:"order";s:0:"";s:5:"align";s:6:"center";s:5:"class";s:0:"";s:5:"reset";s:0:"";}}',
                'lgSize'        => 'a:1:{i:0;a:6:{s:5:"width";s:1:"6";s:6:"offset";s:0:"";s:5:"order";s:0:"";s:5:"align";s:6:"center";s:5:"class";s:0:"";s:5:"reset";s:0:"";}}',
                'xlSize'        => 'a:1:{i:0;a:6:{s:5:"width";s:1:"6";s:6:"offset";s:0:"";s:5:"order";s:0:"";s:5:"align";s:6:"center";s:5:"class";s:0:"";s:5:"reset";s:0:"";}}',
                'align'         => 'center',
                'justify'       => 'center'
            )
        );
    }
}