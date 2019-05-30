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
        $menu->setChildrenAttributes(array('id' => 'slide-out', 'class' => 'sidenav sidenav-fixed', 'currentClass' => 'active'));

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


    /*
     * Generiert das Menü für die Projekt Details.
     */
    public function createProjectDetailsMenu(array $options)
    {
        // Wurzel Menüpunkt, dem die anderen Menüpunkte untergeordent werden
        $menu = $this->factory->createItem('root');
        // Festlegung der class Attribute für die einzelnen Menü elemente, damit das Materilize Design übernommen wird
        $menu->setChildrenAttributes(array('class' => 'tabs tabs-transparent', 'currentClass' => 'active'));
        // derzeitige Projekt ID, die für die Generierung der Links für die einzelnen Unterpunkte benötigt wird
        $routeParameters = array('id' => $this->requestStack->getCurrentRequest()->get('id'));
        // Hinzufügen der Menüunterpunkte
        $menu->addChild('projects_home', array( // Projekt Dashboard
            'label' => 'Home', // Titel für den Menüeintrag
            'route' => 'lc_projects_detail', // Routeverlinkung für den Menüpunkt
            'routeParameters' => $routeParameters, // Projekt ID, für die Route
        ))->setAttribute('icon', 'fas fa-home') // Icon für den Menüeintrag über FontAwesome
        ->setAttribute('class', 'tab'); // benötigte Klasse für Materialize
        $menu->addChild('projects_members', array( // Projekt Mitgliederliste
            'label' => 'Members',
            'route' => 'lc_projects_members',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-book')
        ->setAttribute('class', 'tab');
        $menu->addChild('projects_events', array( // Projekt Terminkalender
            'label' => 'Events',
            'route' => 'lc_projects_events',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-hdd')
        ->setAttribute('class', 'tab');
        $menu->addChild('projects_chat', array( // Projekt Chat
            'label' => 'Chat',
            'route' => 'lc_projects_chat',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-comments')
        ->setAttribute('class', 'tab');
        $menu->addChild('projects_settings', array( // Projekt Einstellungen
            'label' => 'Settings',
            'route' => 'lc_projects_settings',
            'routeParameters' => $routeParameters,
        ))->setAttribute('icon', 'fas fa-cog')
        ->setAttribute('class', 'tab');

        // Durchgehen aller Menüpunkte um zu überprüfen, auf welchem man sich gerade befindet
        foreach ($menu as $key => $item) {
            // Überprüfung, ob der Menüpunkt die derzeitige Route besitzt
            if($this->routeKeyVoter->matchItem($item)){
                // Menüpunkt erhält die Klasse current und active
                $item->setCurrent(true);
                $item->setAttribute('class', 'active');
            }
        }

        // Rückgabe des Menü Objektes
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