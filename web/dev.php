<?php

//Quick 'n' dirty get config contents
$config = file_get_contents(__DIR__ . '/../config/config.yml.dist');
if (is_readable(__DIR__ . '/../config/config.yml')) {
    $config = file_get_contents(__DIR__ . '/../config/config.yml');
}

//Dev mode enabled (regex check)?
preg_match("/\n[\s+]?enable_devmode\:[\s+]?(\w+)/", $config, $matches);
$dmSetting = (isset($matches[1]))
    ? $matches[1]
    : false;




//Check if localhost
switch ($dmSetting) {
    case 'true':
        $devMode = true;
    break;
    case 'local':
        if (substr($_SERVER['HTTP_HOST'], 0, strlen('localhost')) != 'localhost') {
            header("HTTP/1.1 403 Forbidden");
            die("Nope");
        }
        $devMode = true;
    break;
    case 'false':
    case false:
        die("Nope");
    break;
    
}

//Include index
include(__DIR__ . '/index.php');

/* EOF: dev.php */
