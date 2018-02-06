<?php
/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 31.01.2018
 * Time: 17:51
 */

namespace XRayLP\LearningCenterBundle;


use Contao\Database;
use XRayLP\LearningCenterBundle\DataQueries\DataSites;

class ContaoManagerPlugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(LearningCenterBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }
}