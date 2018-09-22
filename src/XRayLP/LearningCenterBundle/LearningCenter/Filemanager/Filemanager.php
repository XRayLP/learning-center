<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\LearningCenter\Filemanager;


use Contao\Dbafs;
use Contao\FilesModel;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\XRayLP\LearningCenterBundle\Entity\File;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Member\FrontendUser;
use App\XRayLP\LearningCenterBundle\Request\UploadFileRequest;
use ZipArchive;

class Filemanager
{
    private $doctrine;

    private $token;

    private $twig;

    private $user;

    private $files;

    private $usedSpace;

    /**
     * @var File $curDir
     */
    private $curDir;

    public function __construct(TokenStorageInterface $tokenStorage, RegistryInterface $doctrine, \Twig_Environment $twig)
    {
        $this->doctrine = $doctrine;
        $this->token = $tokenStorage;
        $this->twig = $twig;
        $this->user =$this->doctrine->getRepository(Member::class)->findOneById($tokenStorage->getToken()->getUser()->id);;
    }

    public function setCurDir(File $currentDirectory)
    {
        $this->curDir = $this->doctrine->getRepository(File::class)->findOneByUuid($currentDirectory->getUuid());
    }

    /**
     * Returns the current directory of the filemanager
     *
     * @return File $curDir
     */
    public function getCurDir(): File
    {
        if (isset($this->curDir))
        {
            return $this->curDir;
        } else {
            return $this->user->getHomeDir();
        }
    }

    /**
     * Returns the percentage of used space of an Users cloud
     *
     * @return float
     */
    public function getUsedSpacePercent()
    {
        $usedSpace = $this->getUsedSpace();
        $maxSpace = $this->user->getCloudSpace();

        $usedSpaceDegree = round(($usedSpace/$maxSpace)*360);
        $usedSpacePercent = round(($usedSpace/$maxSpace)*100);

        return $usedSpacePercent;
    }

    /**
     * @param File[] $files
     * @param $zipname
     *
     * Source: https://www.virendrachandak.com/techtalk/how-to-create-a-zip-file-using-php/
     * @return ZipArchive
     */
    public function createZip($files, $zipname): ZipArchive
    {
        $startDir = $this->doctrine->getRepository(File::class)->findOneByUuid($files[0]->getPid());

        //create new zip
        $zip = new \ZipArchive();
        $zip->open('tmp/'.$zipname.'.zip', ZipArchive::CREATE);
        foreach ($files as $file)
        {
            //add file to zip
            if ($file->getType() === 'file')
            {
                $zip->addFile($file->getPath(), $file->getName());
            }
            //add a whole directory to zip
            else
            {
                $handle = opendir($file->getPath());
                while(false !== ($entry = readdir($handle)))
                {
                    if ($entry != "." && $entry != "..")
                    {
                        $realPath = $file->getPath().'/'.$entry;
                        $zipPath = str_replace($startDir->getPath(), "", $realPath);

                        $zip->addFile($realPath, 'Test/test.jpg');
                    }
                }
                closedir($handle);
            }
        }
        $zip->close();
        return $zip;
    }

    private function getUsedSpace()
    {
        $this->usedSpace = 0;

        $this->getDirSpace($this->user->getHomeDir());

        return $this->usedSpace;
    }

    private function getDirSpace(File $dir)
    {
        if ($dir->getType() == 'folder')
        {
            $files = $this->doctrine->getRepository(File::class)->findByPid($dir->getUuid());
            foreach ($files as $file)
            {
                if ($file->getType() == 'folder')
                {
                    $this->getDirSpace($file);
                } else {
                    $objFile = new \Symfony\Component\HttpFoundation\File\File($file->getPath());
                    $this->usedSpace += $objFile->getSize();
                }
            }
        }
    }

    /**
     * @return object|File[]
     */
    public function loadFiles()
    {
        if ($this->curDir instanceof File) {
            $files = $this->doctrine->getRepository(File::class)->findByPid($this->curDir->getUuid());
        } elseif ($this->user instanceof Member) {
            $files = $this->doctrine->getRepository(File::class)->findByPid($this->user->getHomeDir()->getUuid());
        }
        return $this->sort($files);
    }

    /**
     * @return object|File[]
     */
    public function generateBreadcrumb()
    {
        $breadcrumb = array();
        //check if a subfolder

        if ($this->curDir instanceof File) {
            $curDir = $this->curDir;
        }
        if ($this->user instanceof Member) {
            $homeDir = $this->user->getHomeDir();
        }

        $i=0;
        //get every parent folder of the current folder
        do {
            if (isset($curDir) && $curDir !== $homeDir) {
                $breadcrumb[] = $curDir;

                if (null !== $curDir->getPid()) {
                    $curDir = $this->doctrine->getRepository(File::class)->findOneByUuid($curDir->getPid());
                }
                if ($curDir == $homeDir) {
                    $i++;
                }
            } else {
                $breadcrumb[] = $homeDir;
                break;
            }
        }
        while ($homeDir != $curDir || $i < 2);

        //returns the array in reverse
        return array_reverse($breadcrumb);
    }

    /**
     * @param null|File $file
     * @return array
     */
    public function generateToolbar($file)
    {
        $toolbar = array(
            'isDownload' => false,
            'isDelete' => false,
            'isShare' => false,
            'isEditShare' => false,
        );

        if ($file instanceof File)
        {
            $toolbar['isDownload'] = true;
            $toolbar['isDelete'] = true;

            switch ($file->getShared()) {
                case true:
                    $toolbar['isEditShare'] = true;
                    break;
                case false:
                    $toolbar['isShare'] = true;
                    break;
            }
        }
        return $toolbar;
    }

    public function uploadFile(UploadedFile $uploadedFile)
    {
        $filesize = $uploadedFile->getSize();
        $cloudSpace = $this->user->getCloudSpace();

        if ($cloudSpace > $this->getUsedSpace() + $filesize || true) {


            if (isset($this->curDir)) {
                $uploadDir = $this->curDir;
            } else {
                $uploadDir = $this->user->getHomeDir();
            }

            $filename = $uploadedFile->getClientOriginalName();

            $uploadedFile->move('../' . $uploadDir->getPath(), $filename);

            if (Dbafs::shouldBeSynchronized($uploadDir->getPath() . '/' . $filename)) {
                $objModel = FilesModel::findByPath($uploadDir->getPath() . '/' . $filename);
                if ($objModel === null) {
                    $objModel = Dbafs::addResource($uploadDir->getPath() . '/' . $filename);
                }
                // Update the hash of the target folder
                Dbafs::updateFolderHashes($uploadDir->getPath());
            }
        }
    }

    public function mkdir(string $name)
    {
        $parentFolder = $this->getCurDir();
        $strFile = $parentFolder->getPath().'/'.$name;
        $fs = new Filesystem();
        //creates the folder
        $fs->mkdir('../'.$strFile);
        //creates database entry
        if (Dbafs::shouldBeSynchronized($strFile))
        {
            $objModel = \FilesModel::findByPath($strFile);
            if ($objModel === null)
            {
                $objModel = Dbafs::addResource($strFile);
            }
            // Update the hash of the target folder
            Dbafs::updateFolderHashes($parentFolder->getPath());
        }

    }

    public function removeFile(File $file)
    {
        $parentFolder = $this->getCurDir();

        $fs = new Filesystem();
        $fs->remove('../'.$file->getPath());

        Dbafs::deleteResource($file->getPath());

        Dbafs::updateFolderHashes($parentFolder->getPath());
    }

    public function shareFile(File $file, MemberGroup $memberGroup)
    {
        $file->setOwner($this->user);
        $file->setSharedTstamp(time());
        $file->setShared(1);
        $file->addSharedGroup($memberGroup);

        $em = $this->doctrine->getManager();
        $em->persist($file);
        $em->flush();

        if ($file->getType() == 'folder')
        {
            $childFiles = $this->doctrine->getRepository(File::class)->findBy(['pid' => $file->getUuid()]);
            foreach ($childFiles as $childFile) {
                $this->shareFile($childFile, $memberGroup);
            }
        }


    }

    public function updateShareFile(File $file)
    {
        $file->setSharedTstamp(null);
        $file->setShared(0);
        $file->setSharedGroups(null);

        $em = $this->doctrine->getManager();
        $em->persist($file);
        $em->flush();

        if ($file->getType() == 'folder')
        {
            $childFiles = $this->doctrine->getRepository(File::class)->findBy(['pid' => $file->getUuid()]);
            foreach ($childFiles as $childFile) {
                $this->updateShareFile($childFile);
            }
        }
    }

    public function removeShareFile(File $file, MemberGroup $memberGroup)
    {
        $file->removeSharedGroup($memberGroup);

        $em = $this->doctrine->getManager();
        $em->persist($file);
        $em->flush();

        if ($file->getType() == 'folder')
        {
            $childFiles = $this->doctrine->getRepository(File::class)->findBy(['pid' => $file->getUuid()]);
            foreach ($childFiles as $childFile) {
                $this->removeShareFile($childFile, $memberGroup);
            }
        }
    }

    /**
     * @param File[] $files
     * @return File[] $files
     */
    private function sort($files)
    {
        if (isset($files))
        {
            $filesFolders = array();
            $filesFiles = array();
            //sorts the files by types
            foreach ($files as $file)
            {
                if ($file->getType() == 'folder')
                {
                    /** @var File[] $filesFolders **/
                    $filesFolders[] = $file;
                } else {
                    /** @var File[] $filesFiles **/
                    $filesFiles[] = $file;
                }
            }
            //sorts the folders by name
            for ($i=0; $i<count($filesFolders); $i++)
            {
                // position of the smallest element
                $minpos=$i;
                for ($j=$i+1; $j<count($filesFolders); $j++)
                    if (strtolower($filesFolders[$j]->getName()) < strtolower($filesFolders[$minpos]->getName()))
                        $minpos=$j;
                // change elements
                $tmp=$filesFolders[$minpos];
                $filesFolders[$minpos]=$filesFolders[$i];
                $filesFolders[$i]=$tmp;
            }
            //sorts the files by name
            for ($i=0; $i<count($filesFiles); $i++)
            {
                // position of the smallest element
                $minpos=$i;
                for ($j=$i+1; $j<count($filesFiles); $j++)
                    if (strtolower($filesFiles[$j]->getName()) < strtolower($filesFiles[$minpos]->getName()))
                        $minpos=$j;
                // change elements
                $tmp=$filesFiles[$minpos];
                $filesFiles[$minpos]=$filesFiles[$i];
                $filesFiles[$i]=$tmp;
            }
            //merge the two temporal arrays
            $files = array_merge($filesFolders, $filesFiles);
        }
        return $files;
    }
}