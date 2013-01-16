<?php

namespace Citagora\Web\SilexProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Citagora\Web\Oauth\StateStore;
use UnexpectedValueException;
use Pimple;

class OauthClientProvider implements ServiceProviderInterface
{
    /**
     * @var array  Define Installed Provider Classes Here
     */
    private $installedProviders = array(
        'google'   => "\Citagora\Web\Oauth\GoogleProvider",
        'facebook' => "\Citagora\Web\Oauth\FacebookProvider",
    );

    // --------------------------------------------------------------

    /**
     * Register
     */
    public function register(Application $app)
    {
        //Shortcut
        $providers =& $this->installedProviders;

        $app['oauth_clients'] = $app->share(function($app) use ($providers) {

            //Load the container object to return
            $clients = new Pimple();

            //Get config and state storage
            $config     = $app['oauth_clients.config'];
            $stateStore = $app['oauth_clients.state_store'];

            //Do it...
            foreach($config as $name => $creds) {

                //If there is a class, and the config is set for this provider, add it
                if (isset($providers[$name]) && $creds['key'] && $creds['secret']) {

                    $className = $providers[$name];

                    //Add it to the array
                    $clients[$name] = $clients->share(function() use ($className, $stateStore, $creds)  {
                        return new $className($stateStore, $creds['key'], $creds['secret']);
                    });
                }
            }

            return $clients;
        });
    }

    // --------------------------------------------------------------

    public function boot(Application $app)
    {
        //Nothing, but its required by ServiceProviderInterface
    }    
}

/* EOF: OauthClientProvider.php */