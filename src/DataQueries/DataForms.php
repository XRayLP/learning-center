<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */


namespace XRayLP\LearningCenterBundle\DataQueries;


class DataForms
{
    public static function getDataForms()
    {
        return $forms = array(
            '1' => array(
                'id'            => 1,
                'tstamp'        => time(),
                'title'         => 'Upload',
                'method'        => 'POST',
                'attributes'    => 'a:2:{i:0;s:11:"form-upload";i:1;s:0:"";}'
            ),
            '2' => array(
                'id'            => 2,
                'tstamp'        => time(),
                'title'         => 'Create Folder',
                'method'        => 'POST',
                'attributes'    => 'a:2:{i:0;s:18:"form-create-folder";i:1;s:0:"";}'
            ),
            '3' => array(
                'id'            => 3,
                'tstamp'        => time(),
                'title'         => 'Delete',
                'method'        => 'POST',
                'attributes'    => 'a:2:{i:0;s:11:"form-delete";i:1;s:0:"";}'
            ),
            '4' => array(
                'id'            => 4,
                'tstamp'        => time(),
                'title'         => 'Download',
                'method'        => 'POST',
                'attributes'    => 'a:2:{i:0;s:13:"form-download";i:1;s:0:"";}'
            ),
            '5' => array(
                'id'            => 5,
                'tstamp'        => time(),
                'title'         => 'Share',
                'method'        => 'POST',
                'attributes'    => 'a:2:{i:0;s:10:"form-share";i:1;s:0:"";}'
            ),
        );
    }
}