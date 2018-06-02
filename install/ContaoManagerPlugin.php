<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use XRayLP\LearningCenterBundle\LearningCenterBundle;
use Contao\CoreBundle\ContaoCoreBundle;

class ContaoManagerPlugin implements BundlePluginInterface, RoutingPluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(LearningCenterBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__.'/../src/Resources/config/routing.yml')
            ->load(__DIR__.'/../src/Resources/config/routing.yml')
            ;
    }
}
