<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Menu;


use Contao\CoreBundle\Security\User\ContaoUserProvider;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use XRayLP\LearningCenterBundle\Member\FrontendMember;
use XRayLP\LearningCenterBundle\Security\Authorization\Voter\RouteKeyVoter;

class MenuBuilder
{
    private $factory;
    private $routeKeyVoter;
    private $requestStack;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     * @param RouteKeyVoter $routeKeyVoter
     * @param RequestStack $requestStack
     */
    public function __construct(FactoryInterface $factory, RouteKeyVoter $routeKeyVoter, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->routeKeyVoter = $routeKeyVoter;
        $this->requestStack = $requestStack;
        $this->routeKeyVoter->setRequest($requestStack);
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'nav flex-column', 'currentClass' => 'active'));

        //adding all menu items
        $menu->addChild('home', array(
            'label' => 'Home',
            'route' => 'learningcenter',
        ))->setAttribute('icon', 'fas fa-home');

        $menu->addChild('catalog', array(
            'label' => 'Catalog',
            'route' => 'learningcenter_catalog'
        ))->setAttribute('icon', 'fas fa-book');

        $menu->addChild('files', array(
            'label' => 'Filemanager',
            'route' => 'learningcenter_files'
        ))->setAttribute('icon', 'fas fa-hdd');

        $menu->addChild('projects', array(
            'label' => 'Projects',
            'route' => 'learningcenter_projects',
        ))->setAttribute('icon', 'fas fa-project-diagram');

        $menu->addChild('lists', array(
            'label' => 'Lists',
            'route' => 'learningcenter_user',
        ))->setAttribute('icon', 'fas fa-list-ul');

        //set matching items current
        foreach ($menu as $key => $item) {
            if($this->routeKeyVoter->matchItem($item)){
                $item->setCurrent(true);
            }
        }

        return $menu;
    }


    public function createProjectDetailsMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'nav nav-tabs'));

        $routeParameters = array('alias' => \System::getContainer()->get('request_stack')->getCurrentRequest()->get('alias'));

        //adding all menu items
        $menu->addChild('projects_home', array(
            'label' => 'Home',
            'route' => 'learningcenter_projects.details',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-home');

        $menu->addChild('projects_members', array(
            'label' => 'Members',
            'route' => 'learningcenter_projects.details.members',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-book');

        $menu->addChild('projects_events', array(
            'label' => 'Events',
            'route' => 'learningcenter_projects.details.events',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-hdd');

        $menu->addChild('projects_chat', array(
            'label' => 'Chat',
            'route' => 'learningcenter_projects.details.chat',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-project-diagram');

        //set matching items current
        foreach ($menu as $key => $item) {
            if($this->routeKeyVoter->matchItem($item)){
                $item->setCurrent(true);
            }
        }
        return $menu;
    }
}