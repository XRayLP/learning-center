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
class ModuleProjectsList extends Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_projects_list';

    /**
     * Generate the content element
     */
    protected function compile() {
        
        //create detail page URL
        $pageModel = \PageModel::findByPk(deserialize($this->jumpTo));
        if ($pageModel) {
            $url = \Controller::generateFrontendUrl($pageModel->row());
        }
        
        //Get the current frontend user and his id
        $this->import('FrontendUser', 'User');
        $user = $this->User->id;
        
        //Database Query of all members
        $rs = Database::getInstance()
            ->query('SELECT * FROM tl_projects ORDER BY name');
        $projectList = $rs->fetchAllAssoc();
        
        //Delete all array keys without the current user id
        foreach ($projectList as $key => &$project){
            
            if (in_array($user, deserialize($project['members'])) == false){
                unset($projectList[$key]);
            } else {
                $project['url'] = $url.'?id='.$project['id'];
            }
        }

        $this->Template->projects = $projectList;
        $this->Template->jumpToPage = $url;
    }
}