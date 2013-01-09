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
        $mode = Citagora\Web\App::DEVELOPMENT;
    break;    
    case 'maintenance':
        $mode = Citagora\Web\App::MAINTENANCE;
    break;
    case 'production': default:
        $mode = Citagora\Web\App::PRODUCTION;
    break;
}

//Run it
$app = new Citagora\Web\App($mode);
$app->run();

/* EOF: index.php */