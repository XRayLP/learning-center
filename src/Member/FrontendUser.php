<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Member;


use Contao\FrontendUser as User;

class FrontendUser extends User
{
    protected $roles = array();

    protected function __construct()
    {
        parent::__construct();

    }

    public function addRole(string $role)
    {
        array_push($this->roles, $role);
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }
}