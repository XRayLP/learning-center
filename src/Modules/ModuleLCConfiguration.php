<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Modules;

use XRayLP\LearningCenterBundle\DataQueries\DataCreate;

class ModuleLCConfiguration extends \BackendModule
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_lc_configuration';

    /**
     * Compile the current element
     */
    protected function compile()
    {
        if($_POST['dataQuery'] == 1) {
            DataCreate::createDatabaseEntries();
        }

        $this->Template->content = '';
        $this->Template->href = \Environment::get('request');
        $this->Template->title = \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['backBTTitle']);
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
        $this->Template->token = \RequestToken::get();
    }
}
