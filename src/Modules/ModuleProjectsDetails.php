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
 * Front end module "project_details".
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class ModuleProjectsDetails extends Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = "mod_projects_details";

    /**
     * Generate the content element
     */
    protected function compile()
    {
        $id = $_GET['id'];

        //Get the current frontend user and his id
        $this->import('FrontendUser', 'User');
        $user = $this->User->id;
        
        //Database Query for Project
        $rs = Database::getInstance()
            ->query('SELECT * FROM tl_projects WHERE id='.$id);
        $project = $rs->fetchAllAssoc();

        //checks if the user is in the project
        if (in_array($user, deserialize($project->members))){
            $name = $project->name;
            $date = date("d.m.Y", $project['tstamp']);
            $description = $project['description'];
            $members = deserialize($project['members']);
        }

        $this->Template->project = $project;
        $this->Template->prj_name = $name;
        $this->Template->prj_date = $date;
        $this->Template->prj_description = $description;
        $this->Template->prj_members = $members;
    }

    
}