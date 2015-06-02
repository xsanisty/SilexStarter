<?php
define('CONSOLE', true);

require 'bootstrap.php';

use Symfony\Component\Debug\Debug;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Console\Output\ConsoleOutput;

$errorHandler = ErrorHandler::register();
$excepHandler = ExceptionHandler::register();

$errorHandler->setExceptionHandler(
    function (Exception $e) {
        $output = new ConsoleOutput;

        $output->writeln('<error>'.$e->getMessage().'</error>');
        $output->writeln('<comment>'.$e->getTraceAsString().'</comment>');
    }
);

$app = require 'application.php';
$app->registerServices($app['config']['services.console']);
$app->boot();

/**
 * Search for command and register it
 */
$app['console']->setDispatcher($app['dispatcher']);
$app['console']->registerCommandDirectory($app['path.app'] . 'commands');
$app['console']->registerCommand(new SilexStarter\Console\Command\MigrationCommand);
$app['console']->registerCommand(new SilexStarter\Console\Command\CacheClearCommand);
$app['console']->registerCommand(new SilexStarter\Console\Command\SilexStarterInitCommand);

/**
 * Dispatch console.init event to register command previously registered in module manager
 */
$app['dispatcher']->dispatch('console.init');

return $app['console'];
