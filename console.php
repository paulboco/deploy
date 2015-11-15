#!/usr/bin/env php
<?php require __DIR__ . '/app/helpers.php';
require __DIR__ . '/vendor/autoload.php';

use App\Console\Command\DeployRepoCommand;
use App\Github;
use App\DeployManager;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new DeployRepoCommand(new Github, new DeployManager(__DIR__)));

$application->run();
