<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Service;


use Contao\FilesModel;
use Contao\MemberGroupModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Catalog
{
    protected $member;

    protected $objFiles;

    protected $objCourses;

    protected $files;

    protected $router;

    protected $errors = array();

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;

    }

    /**
     */
    public function setMember($objUser)
    {
        $this->member = new Member($objUser);
    }

    public function getUser(){
        return $this->member->getUserModel();
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function loadFiles(){
        $files = array();

        $this->objFiles = FilesModel::findBy('shared', 1);
        if ($this->objFiles === null) {
            try {
                throw new \Exception('No Shared Files');
            } catch (\Exception $e) {
                array_push($this->errors, $e->getMessage());
            }
        } else {
            if ($this->getUser()->groups != null) {
                $arrGroups = \StringUtil::deserialize($this->getUser()->groups);

                while ($this->objFiles->next()) {
                    if ($this->objFiles->shared == 1) {
                        foreach ($arrGroups as $id) {
                            if ($id == unserialize($this->objFiles->shared_groups)) {
                                $strHref = $this->router->generate('learningcenter_files.download', array('fid' => $this->objFiles->id));
                                $strGroup = MemberGroupModel::findById($id)->name;

                                //add file to array
                                $files[] = array
                                (
                                    'id' => $this->objFiles->id,
                                    'tstamp' => $this->objFiles->shared_tstamp,
                                    'uuid' => $this->objFiles->uuid,
                                    'name' => $this->objFiles->name,
                                    'href' => $strHref,
                                    'filesize' => '',
                                    'extension' => $this->objFiles->extension,
                                    'shared' => $this->objFiles->shared,
                                    'shared_group' => $strGroup,
                                    'shared_time' => date('d.m.Y', $this->objFiles->shared_tstamp)
                                );
                                $this->sortByTimestamp($files);
                            }
                        }
                    }
                }
            } else {
                try {
                    throw new \Exception('No Groups');
                } catch (\Exception $e) {
                    array_push($this->errors, $e->getMessage());
                }
            }
        }
        return $files;
    }

    protected function sortByTimestamp($files) {
        //sorts the files by tstamp
        for ($i=0; $i<count($files); $i++)
        {
            // position of the biggest element
            $minpos=$i;
            for ($j=$i+1; $j<count($files); $j++)
                if ($files[$j]['tstamp']>$files[$minpos]['tstamp'])
                    $minpos=$j;
            // change elements
            $tmp=$files[$minpos];
            $files[$minpos]=$files[$i];
            $files[$i]=$tmp;
        }
    }

}