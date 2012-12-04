<?php

namespace Citagora;

use Silex\Application as SilexApplication;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use RuntimeException;
use Configula\Config;

/**
 * Citagora Main Application and DiC Container
 */
class App extends SilexApplication
{
    const DEVELOPMENT = 1;
    const PRODUCTION = 2;    

    // --------------------------------------------------------------

    /**
     * @var string  Basepath with no trailing slash
     */
    private $basepath;

    /**
     * @var string  Sourcecode path with no trailing slash
     */
    private $apppath;

    // --------------------------------------------------------------

    public function __construct($mode = self::PRODUCTION)
    {
        //
        // Startup
        //

        //Construct the parent
        parent::__construct();

        //Set Basepath and appath
        $this['basepath'] = realpath(__DIR__ . '/../../');
        $this['srcpath']  = realpath(__DIR__);

        //Mode
        if ($mode == self::DEVELOPMENT) {
            $this['debug'] = true;
        }

        //Startup Check
        $this->startupCheck();

        //Load common libraries
        $this->loadCommonLibraries();       
    }

    // --------------------------------------------------------------

    /**
     * Web Run Method
     */
    public function run()
    {
        //
        // Load web libraries
        //

        //$this['session']
        $this->register(
            new SessionServiceProvider()
        );

        //$this['url_generator']
        $this->register(
            new UrlGeneratorServiceProvider()
        );

        //Form Provider
        $this->register(new FormServiceProvider());

        //Notices Provider
        //Copy from HAWK if we want to

        //$this['twig']
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => $this['srcpath'] . '/Views'
        ));

        //Before Hook
        $this->before(array($this, 'beforeHook'));

        //Mount the controllers
        $this->mount('', new Controller\Front());
        $this->mount('', new Controller\Documents($this['dummydocs']));
        $this->mount('', new Controller\Users()); 

        parent::run();
    }

    // --------------------------------------------------------------

    /**
     * Load common libraries
     */
    private function loadCommonLibraries()
    {
        //
        // Load common libraries
        //

        //Shortcut for lexical functions
        $app =& $this;

        //$this['config'] (1st)
        $this['config'] = new Config($this['basepath'] . '/config');

        //$this['monolog']
        $this->register(new Provider\Monolog(), array(
            'monolog.file'   => $this['config']->log_file,
            'monolog.emails' => (array) $this['config']->admin_emails
        ));

        //Translation Service Provider (required by forms provider)
        $this->register(new TranslationServiceProvider(), array(
            'locale_fallback' => 'en',
        ));

        //$this['mongo']
        $this->register(new Provider\DoctrineMongo(), array(
            'mongo.documents_path' => $this['srcpath'] . '/Entities',
            'mongo.params'         => $this['config']->mongodb
        ));

        //$this['dummydocs']
        $this['dummydocs'] = $this->share(function() use ($app) {
            return new Service\DummyDocuments($app['basepath'] . '/tests/fixtures/DocumentDummyRecs.json');
        });

        //$this['validation']
        $this->register(new ValidatorServiceProvider());
    }

    /**
     * Hook after request is generated but before controller is loaded
     */
    public function beforeHook()
    {
        //Some additional info about the path at runtime
        $this['url.base'] = $this['request']->getSchemeAndHttpHost() . $this['request']->getBasePath();
        $this['url.app']  = $this['request']->getSchemeAndHttpHost() . $this['request']->getBaseUrl();

        //Add additional information to Twig
        $this['twig'] = $this->share($this->extend('twig', function($twig, $app) {

            $twig->addGlobal('base_url', $app['url.base']);
            $twig->addGlobal('asset',    $app['url.base'] . '/assets');
            $twig->addGlobal('site_url', $app['url.base']);

            if ($app['debug'] == true) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            return $twig;
        }));      
    }

    // --------------------------------------------------------------

    /**
     * Check a few things, and if fail, throw an exception
     */
    private function startupCheck()
    {
        if ( ! is_readable($this['basepath'] . '/config/config.yml')) {
            throw new RuntimeException("Missing config/config.yml!  Did you forget to set it up?");
        }
    }

}

/* EOF: App.php */