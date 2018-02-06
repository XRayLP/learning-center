<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Classes;

/**
 * Helper class for the file manager.
 *
 * @author Niklas Loos <https://github.com/XRayLP>
 */
class FileManager
{
    protected $strTable = 'tl_files';

    /**
     * Creates a folder from a new folder object.
     * @param object $strPath
     * @return \Contao\FilesModel|NULL|\XRayLP\LearningCenterBundle\Classes\FileManager
     * @throws \Exception
     */
    public static function createFromFolder($strPath)
    {
        if (($objExisting = \FilesModel::findByPath($strPath)) !== null)
        {
            return $objExisting;
        }
        //creates the values for the fields in database & saves the object in database and as folder
        $objFolder = new \Folder($strPath);
        $objModel = new self();
        $objModel->pid = static::findParentByPath($strPath);
        $objModel->tstamp = time();
        $objModel->name = basename($strPath);
        $objModel->type = 'folder';
        $objModel->path = $strPath;
        $objModel->hash = $objFolder->hash;
        $objModel->found = 1;
        $objModel->save();
        return $objModel;
    }
    
    /**
     * Get the PID for an File/Folder
     * @param string $strPath
     * @return int
     */
    public static function findParentByPath($strPath)
    {
        $arrQuery = array();
        $arrPath = explode('/', $strPath);

        //delete the last element of the array
        array_pop($arrPath);

        //set path again together
        for ($i=count($arrPath); $i>0; $i--)
        {
            $arrQuery[] = implode('/', $arrPath);
            unset($arrPath[$i-1]);
        }
        //Database query to get the pid
        return (int) \Database::getInstance()->prepare("SELECT id, LENGTH(path) AS length FROM " . static::$strTable . " WHERE path IN ('" . implode("','", $arrQuery) . "') ORDER BY length DESC")->limit(1)->execute()->id;
    }
    
    /**
     * Get all subfiles of an directory, including the subfiles of subfolders.
     * @param string $dir
     * @return array
     * @throws \Exception
     */
    public static function getAllSubfiles($dir)
    {
        $files = array();
        $arrFiles[] = \FilesModel::findMultipleByUuids(array($dir));

        //get all files of each file collection
        foreach ($arrFiles as &$objSubfiles)
        {
            if ($objSubfiles == null) {
                continue;
            }

            while ($objSubfiles->next()) {

                // Skip subfolders, but add the subfiles of the folder as collection to the array
                if ($objSubfiles->type == 'folder') {
                    $arrFiles[] = \FilesModel::findByPid($objSubfiles->uuid);
                    continue;
                }

                //add the file object to the array
                $files[] = \FilesModel::findByUuid($objSubfiles->uuid);
            }
        }
        return $files;
    }

    /**
     * Get all subfiles and subfolder of an dfirectory, including the root folder.
     * @param string $dir
     * @return array (objects)
     * @throws \Exception
     */
    public static function getAllSubfilesAndFolders($dir)
    {
        $files = array();

        $objDirectory = \FilesModel::findMultipleByUuids(array($dir));
        $arrFiles[] = $objDirectory;

        //get all file and folder objects of each file collection
        foreach ($arrFiles as &$objSubfiles)
        {
            if ($objSubfiles == null) {
                continue;
            }
            while ($objSubfiles->next()) {
                //add folder to files array and the folders' subfiles/folders to the collection array
                if ($objSubfiles->type == 'folder') {
                    $arrFiles[] = \FilesModel::findByPid($objSubfiles->uuid);
                    $files[] = \FilesModel::findByUuid($objSubfiles->uuid);
                    continue;
                }
                //file object to array
                $files[] = \FilesModel::findByUuid($objSubfiles->uuid);
            }
        }
        return $files;
    }

    /**
     * Get the filesize of all subfiles of a folder.
     * @param string $dir
     * @return int
     * @throws \Exception
     */
    public static function getUsedDirSpace($dir)
    {
        $usedCloudSpace = 0;

        //add up all file sizes
        foreach (static::getAllSubfiles($dir) as $file)
        {
            $filesize = (new \File($file->path))->filesize;
            $usedCloudSpace += $filesize;
        }

        return $usedCloudSpace;

    }
}

