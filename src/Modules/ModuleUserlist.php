<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Modules;

use Contao\Module;
use Contao\Database;
use XRayLP\LearningCenterBundle\Classes\UserHelper;

/**
 * Front end module "userlist".
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class ModuleUserlist extends Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_lc_user_list';

    /**
     * Member
     * @var object
     */
    protected $objMember;

    /**
     * Return if there are no files
     *
     * @return string
     */
    public function generate()
    {
        //Get the current frontend user and his groups
        $this->import('FrontendUser', 'User');
        $this->objMember = \MemberModel::findByPk($this->User->id);

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    protected function compile()
    {
        $objGroupClass = UserHelper::getClass($this->objMember);

        if (isset($objGroupClass)) {
            $groupId = $objGroupClass->id;
            
            //Database Query of all members
            $rs = Database::getInstance()
                ->query('SELECT * FROM tl_member ORDER BY firstname');
            $memberList = $rs->fetchAllAssoc();
            
            //Delete all array keys without the current group id
            foreach ($memberList as $key => $member){
                if (in_array($groupId, deserialize($member['groups'])) == false){
                    unset($memberList[$key]);
                } 
            }
        }
        //Twig
        $twigRenderer = \System::getContainer()->get('templating');
        $rendered = $twigRenderer->render('@LearningCenter/mod_lc_user_list.twig',
            [
               'members'    => $memberList
            ]);

        $this->Template->renderedTwig = $rendered;
    }

    
}