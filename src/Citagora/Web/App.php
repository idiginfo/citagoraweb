<?php

namespace Citagora\Web;

use Citagora\App as CitagoraApp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use RuntimeException, Exception;

class App extends CitagoraApp
{
    public function run()
    {
        //Before Hook
        $this->before(array($this, 'loadWebLibraries'));

        //Error Hook
        $this->error(array($this, 'doError'));

        //Maintenance mode?
        if ($this['config']->site_mode == 'maintenance') {
            $this->doMaintenance();
        }
        else {
            
            //Mount the controllers
            $this->mount('', new Controller\Front());             
            $this->mount('', new Controller\Documents());   
            $this->mount('', new Controller\Users());   
        }

        //Go
        parent::run();
    }

    // --------------------------------------------------------------

    /**
     * Hook after request is generated but before controller is loaded
     */
    public function loadWebLibraries()
    {
        //Pointer for lexical functions
        $app =& $this;

        //$this['session']
        $this->register(
            new SessionServiceProvider(), array('session.storage.options' => array(
                'name'            => 'Citagora', 
                'cookie_lifetime' => $this['config']->session_expire
            )
        ));

        //$this['url_generator']
        $this->register(new UrlGeneratorServiceProvider());

        //Form Provider
        $this->register(new FormServiceProvider());

        //$this['twig']
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => $this['srcpath'] . '/Web/Views'
        ));

        //Load account manager (relies on session)
        $this['account'] = $this->share(function($app) {
            return new Library\Account(
                $app['session'],
                $app['em']->getCollection('User')
            );
        });

        //Notices Provider
        $this['notices'] = $this->share(function($app) {
            return new Library\Notices($app['session']);
        });

        //Some additional info about the path at runtime
        $this['url.base']     = $this['request']->getSchemeAndHttpHost() . $this['request']->getBasePath();
        $this['url.app']      = $this['request']->getSchemeAndHttpHost() . $this['request']->getBaseUrl();
        $this['url.current']  = $this['url.app'] . $this['request']->getPathInfo();

        //Add OAuth Providers @TODO: Move this into a provider?
        if ($this['config']->oauth) {
            foreach($this['config']->oauth as $provider => $sp) {

                $url = $this['url.app'] . '/login/' . strtolower($provider);

                switch ($provider) {    
                    case 'facebook':
                        $svc = new \OAuth2\Client\Facebook($sp['key'], $sp['secret'], $url);
                    break;
                    case 'google':
                        $svc = new \OAuth2\Client\Google($sp['key'], $sp['secret'], $url);
                    break;
                }

                if (isset($svc)) {
                    $this['controller_auth']->addOauthProvider($svc);
                }            
            }
        }

        //Add additional information to Twig
        $this['twig'] = $this->share($this->extend('twig', function($twig, $app) {

            //URL globals
            $twig->addGlobal('base_url',    $app['url.base']);
            $twig->addGlobal('asset_url',   $app['url.base'] . '/assets');
            $twig->addGlobal('site_url',    $app['url.app']);
            $twig->addGlobal('current_url', $app['url.current']);

            //debug method
            if ($app['debug'] == true) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            //Notices to print
            $twig->addGlobal('notices', $app['notices']->flush());

            //User Info
            $twig->addGlobal('user', $app['account']);

            return $twig;
        }));      
    }

    // --------------------------------------------------------------

    /**
     * Handles errors when in production mode
     *
     * @param \Exception $exception
     * @param int $code
     */
    public function doError(Exception $exception, $code)
    {
        //Load web libraries up
        if ( ! isset($this['twig'])) {
            @$this->loadWebLibraries();
        }

        //Do the error
        switch ($code) {

            case 404:
                return $this['twig']->render('404.html.twig');
            break;
            default:

                $this['monolog']->addError($exception->getMessage(), array('code' => $code));

                //If Debug, do default (just return to cause this behvior)
                if ($this['debug']) {
                    return;
                }
                elseif (isset($this['twig'])) { //If we've loaded twig...
                    return new Response($this['twig']->render('error.html.twig'));
                }
                else {
                    new Response(
                        "<html><head><title>Citagora</title></head><body>Something went wrong.</title></head></html>"
                    );
                }
            break;
        }
    }

    // --------------------------------------------------------------

    /**
     * Set all routes to point ot the maintenance view
     */
    public function doMaintenance()
    {
        //All routes point to maintenance
        $app =& $this;
        $this->match('/', function() use ($app) {
            $data = $app['twig']->render('maint.html.twig');
            return new Response($data, 503);
        });
        $this->match('{url}', function($url) use ($app) {
            $data = $app['twig']->render('maint.html.twig');
            return new Response($data, 503);
        })->assert('url', '.+');        
    }
}

/* EOF: WebApp.php */