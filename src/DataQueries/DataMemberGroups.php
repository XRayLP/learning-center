<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */


namespace XRayLP\LearningCenterBundle\DataQueries;


class DataMemberGroups
{
    public static function getDataMemberGroups()
    {
        return $groups = array(
            '1' => array(
                'id'        => 1,
                'tstamp'    => time(),
                'name'      => 'Schüler'
            ),
            '2' => array(
                'id'        => 2,
                'tstamp'    => time(),
                'name'      => 'Lehrer'
            )
        );
    }

}