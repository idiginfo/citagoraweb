<?php

namespace Citagora\Common\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Citagora\Common\DataSource;
use Phpoaipmh\Client as OaipmhClient,
    Phpoaipmh\Http\Guzzle as OaipmhGuzzle,
    Phpoaipmh\Endpoint;
use Pimple;

/**
 * Silex Provider for DataSources
 */
class DataSources implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['data_sources'] = $app->share(function($app) {

            //Setup a Generic OAI-PMH endpoint we can clone for use in OAI Harvesters
            $oaiEndpoint = function() {
                $client = new OaipmhClient('', new OaipmhGuzzle());
                return new Endpoint($client);
            };

            //Pimple Container for DataSources
            $ds = new Pimple();

            //For now, the keys should match the slug for each data source...
            $ds['nature'] = $ds->share(function() use ($app, $oaiEndpoint) {
                return new DataSource\Nature($oaiEndpoint());
            });
            $ds['arxiv'] = $ds->share(function() use ($app, $oaiEndpoint) {
                return new DataSource\Arxiv($oaiEndpoint());
            });
            $ds['dummy'] = $ds->share(function() use ($app) {
                return new DataSource\DummyRecs();
            });

            return $ds;
        });
    }

    // --------------------------------------------------------------

    public function boot(Application $app)
    {
        //Nothing, but its required by ServiceProviderInterface
    }
}

/* EOF: DataSources.php */