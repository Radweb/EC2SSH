<?php

namespace Radweb\EC2SSH;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Single Command Application
 * http://symfony.com/doc/current/components/console/single_command_tool.html
 */
class Application extends ConsoleApplication
{
    protected function getCommandName(InputInterface $input)
    {
        return 'run';
    }

    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new RunCommand();
        return $commands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();

        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}