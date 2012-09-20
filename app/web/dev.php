<?php

use Symfony\Component\HttpFoundation\Request;

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if ((isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || !in_array(@$_SERVER['REMOTE_ADDR'], array(
        '127.0.0.1',
        '::1',
    ))
) && strpos($_SERVER['HTTP_HOST'], 'localhost' === false)) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

//Check that app/config/paramaters.yml.dist has been copied and if not, print a friendly message
$paramPath = realpath(__DIR__ . '/../app/config') . DIRECTORY_SEPARATOR;
if ( ! is_readable($paramPath . 'parameters.yml')) {
    exit(sprintf(
        "Missing %sparameters.yml.  Be sure to copy it from %sparameters.yml.dist and,
        if necessary, tweak the settings",
        $paramPath, $paramPath
    ));
}

//Load it up!
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
