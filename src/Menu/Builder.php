<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Menu;


use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->setChildrenAttributes(array('class' => 'nav flex-column'));

        $menu
            ->addChild('Home', array('route' => 'learningcenter'))
            ->setLinkAttribute('class', 'nav-link')
            ->setLabelAttribute('class', 'nav-item')
        ;
        $menu
            ->addChild('Catalog', array('route' => 'learningcenter_catalog'))
            ->setLinkAttribute('class', 'nav-link')
            ->setLabelAttribute('class', 'nav-item')
        ;
        $menu
            ->addChild('Filemanager', array('route' => 'learningcenter_files'))
            ->setLinkAttribute('class', 'nav-link')
            ->setLabelAttribute('class', 'nav-item')
        ;
        $menu
            ->addChild('Projects', array('route' => 'learningcenter_projects'))
            ->setLinkAttribute('class', 'nav-link')
            ->setLabelAttribute('class', 'nav-item')
        ;
        $menu
            ->addChild('Lists', array('route' => 'learningcenter_user'))
            ->setLinkAttribute('class', 'nav-link')
            ->setLabelAttribute('class', 'nav-item')
        ;



        return $menu;
    }
}