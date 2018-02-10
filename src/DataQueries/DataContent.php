<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


class DataContent
{
    public static function getDataContent()
    {
        return $content = array(
            '1' => array(
                'id'        => 1,
                'pid'       => 1,
                'ptable'    => 'tl_article',
                'sorting'   => 128,
                'tstamp'    => time(),
                'type'      => 'bs_gridStart',
                'bs_grid_name'  => 'grid_1',
                'bs_grid'       => 1,
            ),
            '2' => array(
                'id'                => 2,
                'pid'               => 1,
                'ptable'            => 'tl_article',
                'sorting'           => 136,
                'tstamp'            => time(),
                'type'              => 'bs_gridStop',
                'bs_grid_parent'    => 1,
            ),
            '3' => array(
                'id'        => 3,
                'pid'       => 1,
                'ptable'    => 'tl_article',
                'sorting'   => 132,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 1,
            ),
            '4' => array(
                'id'        => 4,
                'pid'       => 2,
                'ptable'    => 'tl_article',
                'sorting'   => 136,
                'tstamp'    => time(),
                'type'      => 'text',
                'headline'  => 'a:2:{s:4:"unit";s:2:"h4";s:5:"value";s:9:"Wilkommen";}',
                'text'      => '<p>{{user::firstname}} {{user::lastname}}, wilkommen im Learning Center des Gymnasium Stephaneum Aschersleben.</p>',
                'customTpl' => 'ce_text_card_lc',
                'cssID'     => 'a:2:{i:0;s:0:"";i:1;s:6:"col-12";}',
            ),
            '5' => array(
                'id'        => 5,
                'pid'       => 3,
                'ptable'    => 'tl_article',
                'sorting'   => 136,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 10
            ),
            '6' => array(
                'id'        => 6,
                'pid'       => 4,
                'ptable'    => 'tl_article',
                'sorting'   => 136,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 9
            ),
            '7' => array(
                'id'        => 7,
                'pid'       => 5,
                'ptable'    => 'tl_article',
                'sorting'   => 136,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 8
            ),
            '8' => array(
                'id'        => 8,
                'pid'       => 6,
                'ptable'    => 'tl_article',
                'sorting'   => 136,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 7
            ),
            '9' => array(
                'id'        => 9,
                'pid'       => 7,
                'ptable'    => 'tl_article',
                'sorting'   => 136,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 2
            ),
            '10' => array(
                'id'        => 10,
                'pid'       => 8,
                'ptable'    => 'tl_article',
                'sorting'   => 136,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 4
            ),
            '11' => array(
                'id'        => 11,
                'pid'       => 2,
                'ptable'    => 'tl_article',
                'sorting'   => 264,
                'tstamp'    => time(),
                'type'      => 'module',
                'module'    => 11,
                'cssID'     => 'a:2:{i:0;s:0:"";i:1;s:6:"col-12";}',
            ),
        );
    }
}