<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Security;


use Contao\FrontendUser;

class LearningCenterUser extends FrontendUser
{


    public function getRoles()
    {
        return array('ROLE_USER', $this->memberType);
    }
}