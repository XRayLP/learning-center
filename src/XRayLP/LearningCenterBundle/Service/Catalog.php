<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Service;


use Contao\FilesModel;
use Contao\MemberGroupModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\XRayLP\LearningCenterBundle\Entity\Member;

class Catalog
{
    /**
     * @var Member
     */
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
     * Sets the Member for creating the Object
     *
     * @param Member $objMember
     */
    public function setMember(Member $objMember)
    {
        $this->member = $objMember;
    }

    /**
     * @return Member
     */
    public function getMember(): Member
    {
        return $this->member;
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

    /**
     * Returns an Array with information about the shared files (sorted)
     *
     * TODO: Use File Objects instead of a normal Array
     *
     * @return array
     */
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
            if ($this->member->getGroups() != null) {
                $arrGroups = $this->member->getGroups();

                while ($this->objFiles->next()) {
                    if ($this->objFiles->type == 'file') {
                        foreach ($arrGroups as $arrGroup) {
                            if (in_array($arrGroup->getId(), unserialize($this->objFiles->shared_groups))) {
                                $strHref = $this->router->generate('learningcenter_files.download', array('fid' => $this->objFiles->id));
                                $strGroup = MemberGroupModel::findById($arrGroup->getId())->name;



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

    /**
     * Sort Files Array by their Timestamp
     *
     * @param $files
     */
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