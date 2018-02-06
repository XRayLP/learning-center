<?php
/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 31.01.2018
 * Time: 17:51
 */

namespace XRayLP\LearningCenterBundle;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use XRayLP\LearningCenterBundle\LearningCenterBundle;
use Contao\CoreBundle\ContaoCoreBundle;

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
