<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventListener;

use App\XRayLP\LearningCenterBundle\Entity\File;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use App\XRayLP\LearningCenterBundle\Entity\Calendar;
use App\XRayLP\LearningCenterBundle\Entity\Event;
use App\XRayLP\LearningCenterBundle\Entity\Member;
use App\XRayLP\LearningCenterBundle\Entity\MemberGroup;
use App\XRayLP\LearningCenterBundle\Entity\Notification;
use App\XRayLP\LearningCenterBundle\Entity\Project;

class PostGenerateSchemaTable
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Array
     * Definition of the entities used inside your contao application
     * (should preferably be outsourced)
     */
    private $ignoredEntities = [
        Member::class, // -> tl_member
        MemberGroup::class, // -> tl_member_group
        Event::class,
        Calendar::class,
        File::class,
    ];

    /**
     * Remove ignored tables / entities from schema
     *
     * @param GenerateSchemaTableEventArgs $args
     */
    public function postGenerateSchemaTable(GenerateSchemaTableEventArgs $args)
    {

        $this->entityManager = \System::getContainer()->get('doctrine.orm.entity_manager');
        $schema = $args->getSchema();
        $databaseName = $this->entityManager->getConnection()->getDatabase();

        $ignoredTables = [];
        foreach ($this->ignoredEntities as $entityName) {
            $ignoredTables[] = $databaseName . '.' . $this->entityManager->getClassMetadata($entityName)->getTableName();
        }

        foreach ($schema->getTableNames() as $tableName) {
            if (in_array($tableName, $ignoredTables)) {
                // remove table from schema
                $schema->dropTable($tableName);
            }

        }

    }
}