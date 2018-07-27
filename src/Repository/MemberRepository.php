<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace XRayLP\LearningCenterBundle\Repository;

use Doctrine\ORM\Mapping;
use Doctrine\ORM\EntityRepository;
use XRayLP\LearningCenterBundle\Entity\Member;

class MemberRepository extends EntityRepository
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
     * @return object|Member
     */
    public function findOneById(int $id): Member
    {
        return parent::findOneBy(array('id' => $id));
    }

    public function findAllLike($phrase) {
        $qb = $this->createQueryBuilder('a')
            ->where('a.firstname LIKE :phrase OR a.lastname LIKE :phrase')
            ->setParameter('phrase', '%'.$phrase.'%')
            ->orderBy('a.lastname', 'ASC')
            ->getQuery();

        return $qb->execute();
    }
}