<?php

namespace Citagora\Common\SilexProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Citagora\Common\EntityManager\Manager;

/**
 * Silex Provider for the Entity Manager
 * Uses Doctrine Mongo ODM with Annotation Driver
 */
class EntityManager extends DoctrineMongo implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        //Register using the parent
        parent::register($app);

        $app['em'] = $app->share(function($app) {

            $dm        = $app['mongo'];
            $namespace = (isset($app['em.namespace']))
                ? $app['em.namespace']
                : null;

            $em = new Manager($dm, $namespace);

            //Collection Registrations
            if (isset($app['em.collections']) && is_array($app['em.collections'])) {

                foreach ($app['em.collections'] as $coll) {
                    $em->addCollection($coll);
                }
            }

            return $em;
        });
    }

    // --------------------------------------------------------------

    public function boot(Application $app)
    {
        //Nothing, but its required by ServiceProviderInterface
    }
}

/* EOF: EntityManager.php */