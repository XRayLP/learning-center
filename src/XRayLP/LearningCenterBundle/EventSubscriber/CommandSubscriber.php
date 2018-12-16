<?php
/**
 * @link https://github.com/XRayLP/learning-center-bundle
 * @copyright Copyright (c) 2018 Niklas Loos <https://github.com/XRayLP>
 * @license GPL-3.0 <https://github.com/XRayLP/learning-center-bundle/blob/master/LICENSE>
 */

namespace App\XRayLP\LearningCenterBundle\EventSubscriber;


use Composer\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;

class CommandSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            ConsoleEvents::COMMAND => 'beforeCommandExecution',
        );
    }

    private function beforCommandExecution(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();

        if ($command->getName() == 'console.command.contao_corebundle_command_doctrinemigrationsdiffcommand') {
            $command->setCode('');
        }
    }
}