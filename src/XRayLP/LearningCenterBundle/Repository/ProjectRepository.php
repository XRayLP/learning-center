<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use App\XRayLP\LearningCenterBundle\Entity\Project;

class ProjectRepository extends EntityRepository
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
     * @param $var
     * @return array(Project)
     */
    public function findAllByUserId($var): array
    {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere($qb->expr()->like('p.members', '%1%'))
            ->setParameter(
                1, 'p.id')
        ;

        return $qb->getQuery()->execute();
    }

    /**
     * @param array $groupIds
     * @return Project
     */
    public function findByGroups(array $groupIds)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->add('where', $qb->expr()->in('p.group', $groupIds))
        ;
        return $qb->getQuery()->execute();

    }

    /**
     * @param int $id
     * @return Project|object
     */
    public function findOneById(int $id)
    {
        return parent::findOneBy(array('id' => $id));
    }
}