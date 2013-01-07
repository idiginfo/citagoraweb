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
        $this->register(new Provider\Monolog(), array(
            'monolog.file'   => $this['config']->log_file,
            'monolog.emails' => (array) $this['config']->admin_emails
        ));

        //Translation Service Provider (required by forms provider)
        $this->register(new TranslationServiceProvider(), array(
            'locale_fallback' => 'en',
        ));

        //$this['mailer']
        $this['mailer'] = $this->share(function($app) {
            return new Tool\Mailer('system-noreply@citagora.com', 'Citagora');
        });

        //$this['mongo']
        $this->register(new Provider\DoctrineMongo(), array(
            'mongo.documents_path' => $this['srcpath'] . '/Document',
            'mongo.params'         => $this['config']->mongodb
        ));

        //Guzzle Library
        $this['guzzle'] = $this->share(function() {
            return new \Guzzle\Http\Client();
        });

        //$this['em']
        $this->register(new Provider\EntityManager(), array(
            'em.documentManager' => $this['mongo'],
            'em.namespace'       => __NAMESPACE__ . '\\' . 'Entity',
            'em.collections'     => array(
                new EntityCollection\UserCollection(new BcryptHasher()),
                new EntityCollection\DocumentCollection()
            )
        ));

        $this['data_sources'] = $this->loadDataSources();

        //Document Factory
        $this['document_factory'] = $this->share(function($app) {
            return new Tool\DocumentFactory($app['em']);
        });

        //$this['harvester']
        $this['harvester'] = $this->share(function($app) {
            
            $hvstr = new Harvester\Harvester(
                $app['document_factory'],
                $app['em']->getCollection('Document\Document')
            );

            $hvstr->setEventDispatcher($app['dispatcher']);
            return $hvstr;
        });

        //$this['validation']
        $this->register(new ValidatorServiceProvider());
    }

    // --------------------------------------------------------------

    /**
     * Load Data Sources
     *
     * In its own method to save complexity..
     *
     * @return Pimple
     */
    protected function loadDataSources()
    {
        //Reference
        $app =& $this;

        //Setup a Generic OAI-PMH endpoint we can clone for use in OAI Harvesters
        $app['oai_endpoint'] = function($app) {
            $client = new \Phpoaipmh\Client('', new \Phpoaipmh\Http\Guzzle());
            return new \Phpoaipmh\Endpoint($client);
        };

        //Pimple Container for DataSources
        $ds = new Pimple();

        //For now, the keys should match the slug for each data source...
        $ds['nature'] = $ds->share(function() use ($app) {
            return new DataSource\Nature($app['oai_endpoint']);
        });
        $ds['arxiv'] = $ds->share(function() use ($app) {
            return new DataSource\Arxiv($app['oai_endpoint']);
        });
        $ds['dummy'] = $ds->share(function() use ($app) {
            return new DataSource\DummyRecs();
        });

        return $ds;
    }
}

/* EOF: App.php */