<?php

namespace Citagora;

use Silex\Application as SilexApplication;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Illuminate\Hashing\BcryptHasher;
use RuntimeException, Exception;
use Configula\Config;
use Pimple;

/**
 * Main Citagora Application Library
 */
abstract class App extends SilexApplication
{
    const DEVELOPMENT = 1;
    const PRODUCTION  = 2;
    const MAINTENANCE = 3;

    /**
     * Static entry point for application
     *
     * Will run CLI or web dependening on if running CLI mode or not
     */
    public static function main($mode = self::PRODUCTION)
    {
        $className = get_called_class();
        $that = new $className($mode);
        $that->run();
    }

    // --------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct($mode = self::PRODUCTION)
    {
        //Construct the parent
        parent::__construct();

        //Set Basepath and appath
        $this['basepath'] = realpath(__DIR__ . '/../../');
        $this['srcpath']  = realpath(__DIR__);

        //Load Config (before everything else) $this['config']
        if ( ! is_readable($this['basepath'] . '/config/config.yml')) {
            throw new RuntimeException("Missing config/config.yml!  Did you forget to set it up?");
        }
        $this['config'] = new Config($this['basepath'] . '/config');

        //App mode
        if ($mode == self::DEVELOPMENT) {
            $this['debug'] = true;
        }

        //Load common libraries
        $this->loadCommonLibraries();       
    }

    // --------------------------------------------------------------

    /**
     * Load common libraries
     * 
     * Needed for both CLI and web application
     */
    protected function loadCommonLibraries()
    {
        //Pointer for anonymous functions
        $app =& $this;

        //$this['monolog']
        $this->register(new Common\Provider\Monolog(), array(
            'monolog.file'   => $this['config']->log_file,
            'monolog.emails' => (array) $this['config']->admin_emails
        ));

        //Translation Service Provider (required by forms provider)
        $this->register(new TranslationServiceProvider(), array(
            'locale_fallback' => 'en',
        ));

        //$this['mongo']
        $this->register(new Common\Provider\DoctrineMongo(), array(
            'mongo.documents_path' => $this['srcpath'] . '/Document',
            'mongo.params'         => $this['config']->mongodb
        ));

        //Guzzle Library
        $this['guzzle'] = $this->share(function() {
            return new \Guzzle\Http\Client();
        });

        //$this['em']
        $this->register(new Common\Provider\EntityManager(), array(
            'em.documentManager' => $this['mongo'],
            'em.namespace'       => __NAMESPACE__ . "\\Common\\Entity",
            'em.collections'     => array(
                new Common\EntityCollection\UserCollection(new BcryptHasher()),
                new Common\EntityCollection\DocumentCollection()
            )
        ));

        //$this['data_sources']
        $this->register(new Common\Provider\DataSources(), array(
            'data_sources.config' => $this['config']->datasource_keys
        ));

        //Document Factory
        $this['document_factory'] = $this->share(function($app) {
            return new Common\Tool\DocumentFactory($app['em']);
        });

        //$this['harvester']
        $this['harvester'] = $this->share(function($app) {
            
            $hvstr = new Common\Harvester\Harvester(
                $app['document_factory'],
                $app['em']->getCollection('Document\Document')
            );

            $hvstr->setEventDispatcher($app['dispatcher']);
            return $hvstr;
        });

        //$this['validation']
        $this->register(new ValidatorServiceProvider());
    }
}
/* EOF: App.php */