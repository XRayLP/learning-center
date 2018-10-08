<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

/**
 * Created by PhpStorm.
 * User: nikla
 * Date: 05.10.2018
 * Time: 12:28
 */

namespace App\XRayLP\LearningCenterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;

/**
 * @ORM\Entity
 * @ORM\Table(name="tl_message_metadata", options={"engine":"InnoDB"})
 *
 * src: https://github.com/FriendsOfSymfony/FOSMessageBundle/blob/master/Resources/doc/01a-orm-models.md
 */
class MessageMetadata extends BaseMessageMetadata
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="App\XRayLP\LearningCenterBundle\Entity\Message",
     *   inversedBy="metadata"
     * )
     * @var \FOS\MessageBundle\Model\MessageInterface
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\XRayLP\LearningCenterBundle\Entity\Member")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;
}