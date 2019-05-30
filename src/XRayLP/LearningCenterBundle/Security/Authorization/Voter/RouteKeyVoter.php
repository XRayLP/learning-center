<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Security\Authorization\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Voter based on routing configuration
 */
class RouteKeyVoter implements VoterInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(RequestStack $request = null)
    {
        $this->request = $request;
    }

    public function setRequest(RequestStack $request)
    {
        $this->request = $request;
    }

    public function matchItem(ItemInterface $item)
    {
        if (null === $this->request) {
            return null;
        }

        $routeKeys = explode('|', $this->request->getCurrentRequest()->get('_menu_key'));

        foreach ($routeKeys as $key) {
            if (!is_string($key) || $key == '') {
                continue;
            }

            // Compare the key(s) defined in the routing configuration to the name of the menu item
            if ($key == $item->getName()) {
                return true;
            }
        }

        return null;
    }
}