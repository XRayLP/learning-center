<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


class DataThemes
{
    public static function getDataThemes()
    {
        return $themes = array(
            '1' => array(
                'id'        => 1,
                'tstamp'    => time(),
                'name'      => 'Learning Mangagement System',
                'author'    => 'Niklas Loos; Alexander Krauß'
            )
        );
    }
}