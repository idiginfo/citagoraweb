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
        //Construct the parent
        parent::__construct();

        //Set Basepath and appath
        $this->basepath = realpath(__DIR__ . '/../../');
        $this->apppath  = realpath(__DIR__);

        //Mode
        if ($mode == self::DEVELOPMENT) {
            $this['debug'] = true;
        }

        //Startup Check
        $this->startupCheck();

        //$this['config']
        $this['config'] = new Config($this->basepath . '/config');

        //$this['monolog']
        $this->register(new Provider\Monolog(), array(
            'monolog.file'   => $this['config']->log_file,
            'monolog.emails' => (array) $this['config']->admin_emails
        ));

        //$this['session']
        $this->register(
            new SessionServiceProvider()
        );

        //$this['url_generator']
        $this->register(
            new UrlGeneratorServiceProvider()
        );

        //Notices Provider
        //Copy from HAWK if we want to

        //$this['twig']
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => $this->apppath . '/Views'
        ));

        //Translation Service Provider (required by forms provider)
        $this->register(new TranslationServiceProvider(), array(
            'locale_fallback' => 'en',
        ));

        //$this['mongo']
        $this->register(new Provider\DoctrineMongo(), array(
            'mongo.documents_path' => $this->apppath . '/Entities',
            'mongo.params'         => $this['config']->mongodb
        ));

        //Validation Provider
        $this->register(new ValidatorServiceProvider());

        //Form Provider
        $this->register(new FormServiceProvider());

        //Before Hook
        $this->before(array($this, 'beforeHook'));

        //Mount the controllers
        $this->mount('', new Controller\Front());
        $this->mount('', new Controller\Papers());
    }

    // --------------------------------------------------------------

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

            return $twig;
        }));
    }

    // --------------------------------------------------------------

    /**
     * Check a few things, and if fail, throw an exception
     */
    private function startupCheck()
    {
        if ( ! is_readable($this->basepath . '/config/config.yml')) {
            throw new RuntimeException("Missing config/config.yml!  Did you forget to set it up?");
        }
    }

}

/* EOF: App.php */