<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Menu;


use Contao\CoreBundle\Security\User\ContaoUserProvider;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\XRayLP\LearningCenterBundle\Member\FrontendMember;
use App\XRayLP\LearningCenterBundle\Security\Authorization\Voter\RouteKeyVoter;

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
        $menu->setChildrenAttributes(array('class' => 'sidenav sidenav-fixed', 'currentClass' => 'active'));

        //adding all menu items
        $menu->addChild('home', array(
            'label' => 'Home',
            'route' => 'lc_dashboard',
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
            'route' => 'lc_projects_list',
        ))->setAttribute('icon', 'fas fa-project-diagram');

        $menu->addChild('grade', array(
            'label' => 'Grade',
            'route' => 'lc_grade'
        ))->setAttribute('icon', 'fas fa-chalkboard-teacher');

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
        $menu->setChildrenAttributes(array('class' => 'tabs tabs-transparent', 'currentClass' => 'active'));

        $routeParameters = array('alias' => $this->requestStack->getCurrentRequest()->get('id'));

        //adding all menu items
        $menu->addChild('projects_home', array(
            'label' => 'Home',
            'route' => 'lc_projects_detail',
            'routeParameters' => array('id' => $this->requestStack->getCurrentRequest()->get('id')),
        ))->setAttribute('icon', 'fas fa-home')
        ->setAttribute('class', 'tab');

        $menu->addChild('projects_members', array(
            'label' => 'Members',
            'route' => 'lc_projects_members',
            'routeParameters' => array('id' => $this->requestStack->getCurrentRequest()->get('id')),
        ))->setAttribute('icon', 'fas fa-book')
        ->setAttribute('class', 'tab');

        $menu->addChild('projects_events', array(
            'label' => 'Events',
            'route' => 'lc_projects_events',
            'routeParameters' => array('id' => $this->requestStack->getCurrentRequest()->get('id')),
        ))->setAttribute('icon', 'fas fa-hdd')
        ->setAttribute('class', 'tab');

        $menu->addChild('projects_chat', array(
            'label' => 'Chat',
            'route' => 'lc_projects_chat',
            'routeParameters' => array('id' => $this->requestStack->getCurrentRequest()->get('id')),
        ))->setAttribute('icon', 'fas fa-comments')
        ->setAttribute('class', 'tab');

        $menu->addChild('projects_settings', array(
            'label' => 'Settings',
            'route' => 'lc_projects_settings',
            'routeParameters' => array('id' => $this->requestStack->getCurrentRequest()->get('id')),
        ))->setAttribute('icon', 'fas fa-cog')
        ->setAttribute('class', 'tab');

        //set matching items current
        foreach ($menu as $key => $item) {
            if($this->routeKeyVoter->matchItem($item)){
                $item->setCurrent(true);
                $item->setAttribute('class', 'active');
            }
        }
        return $menu;
    }

    public function createGradeMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('class' => 'tabs tabs-transparent', 'currentClass' => 'active'));

        //adding all menu items
        $menu->addChild('grade_home', array(
            'label' => 'home',
            'route' => 'lc_grade',
        ))->setAttribute('icon', 'fas fa-home')
        ->setAttribute('class', 'tab');

        $menu->addChild('grade_files', array(
            'label' => 'files',
            'route' => 'lc_grade',
        ))->setAttribute('icon', 'fas fa-file')
        ->setAttribute('class', 'tab');

        $menu->addChild('grade_chat', array(
            'label' => 'chat',
            'route' => 'lc_grade_chat',
        ))->setAttribute('icon', 'fas fa-comments')
        ->setAttribute('class', 'tab');

        return $menu;
    }
}