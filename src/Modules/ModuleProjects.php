<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Modules;

use Contao\Database;
use Contao\Module;

/**
 * Front end module "project_list".
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class ModuleProjects extends Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_lc_twig';

    public function generate()
    {
        $this->import('FrontendUser', 'User');

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    protected function compile() {

        //Project Service
        $projects = \System::getContainer()->get('learningcenter.projects')->getProjectsByUser($this->User->id);

        //Twig
        $twigRenderer = \System::getContainer()->get('templating');
        $rendered = $twigRenderer->render('@LearningCenter/mod_lc_projects.twig',
            [
                'projects'      => $projects,
            ]);

        //PHP Template
        $this->Template->renderedTwig = $rendered;

    }
}