<?php

//Autoload
require(__DIR__ . '/../vendor/autoload.php');

//Mode not previously set by dev.php or maint.php?
if ( ! isset($mode)) {
    $mode = 'production';
}

//Site mode
switch ($mode) {
    case 'development':
        $mode = Citagora\WebApp::DEVELOPMENT;
    break;    
    case 'maintenance':
        $mode = Citagora\WebApp::MAINTENANCE;
    break;
    case 'production': default:
        $mode = Citagora\WebApp::PRODUCTION;
    break;
}

//Run it
$app = new Citagora\WebApp($mode);
$app->run();

/* EOF: index.php */