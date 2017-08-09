#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use LearnosityQti\Commands\ConvertToLearnosityCommand;
use LearnosityQti\Commands\ConvertToQtiCommand;
use LearnosityQti\Commands\DefaultCommand;
use LearnosityQti\Commands\ListCommand;
use Symfony\Component\Console\Application;

if (!ini_get('date.timezone')) {
    ini_set('date.timezone', 'UTC');
}

$application = new Application();

/*
|--------------------------------------------------------------------------
| Define the application commands
|--------------------------------------------------------------------------
|
| Setup all the available commands for mo
|
 */
$availableCommands = [
    'list',
    'convert:to:learnosity',
    'convert:to:qti'
];

// Default to `DefaultCommand`
$commandToRun = null;

foreach ($argv as $i => $input) {
    if ($i > 0) {
        if (in_array($input, $availableCommands)) {
            $commandToRun = $input;
            break;
        }
    }
}

switch ($commandToRun) {
    case 'list':
        $application->add(new ListCommand());
        break;
    case 'convert:to:learnosity':
        $application->add(new ConvertToLearnosityCommand());
        break;
    case 'convert:to:qti':
        $application->add(new ConvertToQtiCommand());
        break;
    default:
        $application->add(new DefaultCommand());
}

$application->setDefaultCommand('mo');
$application->run();
