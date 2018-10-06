<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Repository;

use App\XRayLP\LearningCenterBundle\Entity\Thread;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;

class ThreadRepository extends EntityRepository
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
     * @return object|Thread
     */
    public function findOneById(int $id): Thread
    {
        return parent::findOneBy(array('id' => $id));
    }
}