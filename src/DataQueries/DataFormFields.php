<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


class DataFormFields
{
    public static function getDataFormFields()
    {
        return $fields = array(
            '1' => array(
                'id'            => 1,
                'pid'           => 1,
                'sorting'       => 128,
                'tstamp'        => time(),
                'type'          => 'upload',
                'name'          => 'upload',
                'mandatory'     => 1,
                'storeFile'     => 1,
                'useHomeDir'    => 1,
            ),
            '2' => array(
                'id'        => 2,
                'pid'       => 2,
                'sorting'   => 128,
                'tstamp'    => time(),
                'type'      => 'folder',
                'name'      => 'createfolder',
                'mandatory' => 1,
            ),
            '3' => array(
                'id'        => 3,
                'pid'       => 3,
                'sorting'   => 32,
                'tstamp'    => time(),
                'type'      => 'delete',
                'name'      => 'files-delete',
                'mandatory' => 1,
            ),
            '4' => array(
                'id'        => 4,
                'pid'       => 4,
                'sorting'   => 64,
                'tstamp'    => time(),
                'type'      => 'explanation',
                'text'      => '<p>Möchtest du diese Dateien wirklich löschen?</p>',
            ),
            '5' => array(
                'id'        => 5,
                'pid'       => 5,
                'sorting'   => 128,
                'tstamp'    => time(),
                'type'      => 'share',
                'name'      => 'groups',
                'mandatory' => 1,
                'options'   => 'a:0:{}',
            ),
        );
    }
}