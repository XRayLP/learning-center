<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Repository;

use Doctrine\ORM\Mapping;
use Doctrine\ORM\EntityRepository;
use App\XRayLP\LearningCenterBundle\Entity\File;

class FileRepository extends EntityRepository
{
    public function __construct($em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }

    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @return object|File
     */
    public function findOneById(int $id): File
    {
        return parent::findOneBy(array('id' => $id));
    }

    /**
     * @param mixed $uuid
     * @return object|File
     */
    public function findOneByUuid($uuid): File
    {
        return parent::findOneBy(array('uuid' => $uuid));
    }

    /**
     * @param $pid
     * @return object|File[]
     */
    public function findByPid($pid)
    {
        return parent::findBy(array('pid' => $pid));
    }
}