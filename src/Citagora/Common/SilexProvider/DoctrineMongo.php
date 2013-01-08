<?php

namespace Citagora\Common\SilexProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Mongo;
use Doctrine\Common\ClassLoader as MongoClassLoader,
    Doctrine\Common\Annotations\AnnotationReader,
    Doctrine\ODM\MongoDB\DocumentManager,
    Doctrine\MongoDB\Connection as MongoConnection,
    Doctrine\ODM\MongoDB\Configuration as MongoConfiguration,
    Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

/**
 * Silex Provider for Doctrine Mongo ODM
 * Uses Doctrine Mongo ODM with Annotation Driver
 */
class DoctrineMongo implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongo'] = $app->share(function($app) {

            AnnotationDriver::registerAnnotationClasses();

            //Config
            $config = new MongoConfiguration();
            $config->setProxyDir(sys_get_temp_dir());
            $config->setProxyNamespace('Proxies');
            $config->setHydratorDir(sys_get_temp_dir());
            $config->setHydratorNamespace('Hydrators');
            $config->setMetadataDriverImpl(AnnotationDriver::create($app['mongo.documents_path']));
            $config->setDefaultDB($app['mongo.params']['dbname']);

            //Mongo Connection Configuration
            $conn = (isset($app['mongo.connection_uri']) && $app['mongo.params']['connstring'])
                ? new MongoConnection(new Mongo($app['mongo.params']['connstring']))
                : new MongoConnection();
    
            //Set it up
            return DocumentManager::create($conn, $config);
        });
    }

    // --------------------------------------------------------------

    public function boot(Application $app)
    {
        //Nothing, but its required by ServiceProviderInterface
    }
}

/* EOF: DoctrineMongo.php */