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
 * Front end module "catalog".
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class ModuleCatalog extends Module
{
    
    /**
     * Files object
     * @var Model\Collection|FilesModel
     */
    protected $objFiles;
    
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_catalog';

    /**
     * Return if there are no files
     *
     * @return string
     */
    public function generate()
    {
        $groupID = $_GET['gid'];
        $this->isHomepage = true;

        if (isset($groupID)) {
            //Database Query of all members
            $rs = Database::getInstance()
                ->query('SELECT uuid FROM tl_files WHERE shared="1" AND type="file"');
            $this->multiSRC = $rs->fetchEach("uuid");

            // Return if there are no files
            if (!\is_array($this->multiSRC) || empty($this->multiSRC)) {
                return '';
            }

            // Get the file entries from the database
            $this->objFiles = \FilesModel::findMultipleByUuids($this->multiSRC);

            if ($this->objFiles === null) {
                return '';
            }

            $file = \Input::get('file', true);

            // Send the file to the browser and do not send a 404 header (see #4632)
            if ($file != '' && !preg_match('/^meta(_[a-z]{2})?\.txt$/', basename($file))) {
                while ($this->objFiles->next()) {
                    if ($file == $this->objFiles->path || \dirname($file) == $this->objFiles->path) {
                        \Controller::sendFileToBrowser($file);
                    }
                }

                $this->objFiles->reset();
            }
            $this->isHomepage = false;
            $this->groupID = $groupID;
        }
        return parent::generate();
    }
    
    
    /**
     * Generate the content element
     */
    protected function compile()
    {
        /** @var PageModel $objPage */
        global $objPage;
        
        $files = array();
        $courses = array();

        $objFiles = $this->objFiles;

        $this->import('FrontendUser', 'User');
        $objCourses = UserHelper::getCourses($this->User);

        /*
         * Student or Teacher
         */
        $isStudent = UserHelper::isStudent($this->User);
        $isTeacher = UserHelper::isTeacher($this->User);

        $isHomepage = $this->isHomepage;

        //on homepage only the subjects are displayed
        if ($isHomepage) {
            foreach ($objCourses as $objCourse)
            {
                //get the current url
                $strHref = \Environment::get('request');

                // Remove an existing file parameter
                if (preg_match('/(&(amp;)?|\?)gid=/', $strHref))
                {
                    $strHref = preg_replace('/(&(amp;)?|\?)gid=[^&]+/', '', $strHref);
                }

                //add the groupd id as get attribute to url
                $strHref .= (strpos($strHref, '?') !== false ? '&amp;' : '?') . 'gid=' . \System::urlEncode($objCourse->id);

                //add course to array
                $courses[$objCourse->id] = [
                    'id'        => $objCourse->id,
                    'name'      => $objCourse->name,
                    'subject'   => explode(' ', $objCourse->name)[1],
                    'course'    => explode(' ', $objCourse->name)[2],
                    'href'      => $strHref,
                ];
            }
            $this->Template->courses = $courses;

        //files of the chosen course are listed
        } else {
            // Get all files
            while ($objFiles->next()) {

                // Continue if the files has been processed or does not exist
                if (isset($files[$objFiles->path]) || !file_exists(TL_ROOT . '/' . $objFiles->path)) {
                    continue;
                }

                if ($objFiles->type == 'file') {

                    $objFile = new \File($objFiles->path);

                    $strHref = \Environment::get('request');

                    // Remove an existing file parameter (see #5683)
                    if (preg_match('/(&(amp;)?|\?)file=/', $strHref)) {
                        $strHref = preg_replace('/(&(amp;)?|\?)file=[^&]+/', '', $strHref);
                    }

                    //url with file id
                    $strHref .= (strpos($strHref, '?') !== false ? '&amp;' : '?') . 'file=' . \System::urlEncode($objFiles->path);

                    //add the file to array
                    $files[$objFiles->path] = array
                    (
                        'id' => $objFiles->id,
                        'uuid' => $objFiles->uuid,
                        'name' => $objFile->basename,
                        'title' => \StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['download'], $objFile->basename)),
                        'href' => $strHref,
                        'filesize' => $this->getReadableSize($objFile->filesize, 1),
                        'icon' => \Image::getPath($objFile->icon),
                        'extension' => $objFile->extension,
                        'path' => $objFile->dirname,
                        'groups' => $objFiles->shared_groups
                    );
                }
            }
            //get the current subject/course
            $objGroup = \MemberGroupModel::findById($this->groupID);
            $course = array(
                'id'        => $objGroup->id,
                'name'      => $objGroup->name,
                'subject'   => explode(' ', $objGroup->name)[1],
                'course'    => explode(' ', $objGroup->name)[2],
            );

            $this->Template->files = static::getFilesBySharedGroup($this->groupID, $files);
            $this->Template->course = $course;
        }


        $this->Template->isStudent = $isStudent;
        $this->Template->isTeacher = $isTeacher;
        $this->Template->isHomepage = $isHomepage;
    }

    /**
     * Get all files that are shared to a specific group
     *
     * @param int $group
     * @param array $arrFiles
     * @return array
     */
    public static function getFilesBySharedGroup($group, $arrFiles)
    {
        $files = array();

        //check for each shared file whether its shared group match with the group
        foreach ($arrFiles as $file) {
            $file['groups'] = unserialize($file['groups']);
            
            if ($file['groups'] == $group)
            {
                array_push($files, $file);
            }
        }
        return $files;
    }

}



