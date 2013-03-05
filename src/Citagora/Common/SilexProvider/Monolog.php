<?php

namespace Citagora\Common\SilexProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Monolog\Logger,
    Monolog\Handler\StreamHandler,
    Monolog\Handler\NativeMailerHandler,
    Monolog\Handler\FirePHPHandler,
    Monolog\Handler\ChromePHPHandler;

/**
 * Silex Provider for Monolog
 */
class Monolog implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['monolog'] = $app->share(function($app) {

            $file   = $app['monolog.file'];
            $emails = $app['monolog.emails'];

            $logger = new Logger('Citagora');

            if ($file) {
                $logger->pushHandler(new StreamHandler($file, Logger::WARNING));
            }

            if ($emails) {
                $logger->pushHandler(new NativeMailerHandler(
                    $emails,
                    'Citagora Web SysAdmin Notice',
                    'system@citagora.com',
                    Logger::ERROR
                ));
            }

            //If debug mode, also register FirePHP and/or ChromePHP handlers
            if ($app['debug'] === true) {

                if (class_exists('\FirePHP')) {
                    $logger->pushHandler(new FirePHPHandler());
                }

                if (class_exists('\ChromePhp')) {
                    $logger->pushHandler(new ChromePHPHandler());
                }

            }

            return $logger;
        });
    }

    // --------------------------------------------------------------

    public function boot(Application $app)
    {
        //Nothing, but its required by ServiceProviderInterface
    }
}

/* EOF: Monolog.php */