<?php

//Autoload
require(__DIR__ . '/../vendor/autoload.php');

//Dev mode default to off if not previously set
$runMode = (isset($devMode) && $devMode == true)
    ? Citagora\App::DEVELOPMENT
    : Citagora\App::PRODUCTION;

//Run it
$app = new Citagora\WebApp($runMode);
$app->run();

/* EOF: index.php */