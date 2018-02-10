<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\DataQueries;


class DataArticles
{
    public static function getDataArticles()
    {
        return $articles = array(
            '1' => array(
                'id'        => 1,
                'pid'       => 4,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Login',
                'alias'     => 'login',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
            ),
            '2' => array(
                'id'        => 2,
                'pid'       => 5,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Startseite',
                'alias'     => 'startseite',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
                'cssID'     => 'a:2:{i:0;s:9:"dashboard";i:1;s:0:"";}',
            ),
            '3' => array(
                'id'        => 3,
                'pid'       => 6,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Lernkatalog',
                'alias'     => 'lernkatalog',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
            ),
            '4' => array(
                'id'        => 4,
                'pid'       => 7,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Speicher',
                'alias'     => 'speicher',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
            ),
            '5' => array(
                'id'        => 5,
                'pid'       => 8,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Projekte',
                'alias'     => 'projekte',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
            ),
            '6' => array(
                'id'        => 6,
                'pid'       => 9,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Listen',
                'alias'     => 'listen',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
            ),
            '7' => array(
                'id'        => 7,
                'pid'       => 10,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Profil',
                'alias'     => 'profil',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
            ),
            '8' => array(
                'id'        => 8,
                'pid'       => 11,
                'sorting'   => 128,
                'tstamp'    => time(),
                'title'     => 'Abmelden',
                'alias'     => 'abmelden',
                'author'    => 1,
                'inColumn'  => 'main',
                'published' => 1,
            ),
        );
    }
}