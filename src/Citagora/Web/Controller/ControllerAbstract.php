<?php

namespace Citagora\Web\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Form\Form;

/**
 * Abstract Controller for simplifying code in other controllers
 */
abstract class ControllerAbstract implements ControllerProviderInterface
{
    const ALL = 1;

    /**
     * @var Silex\App
     */
    private $app;

    /**
     * @var Silex\ControllerCollection
     */
    private $routes;

    // --------------------------------------------------------------

    /**
     * Connect method
     *
     * @param Silex\Application $app
     */
    public function connect(Application $app)
    {
        //Setup the app as a controller variable
        $this->app    = $app;
        $this->routes = $app['controllers_factory'];

        //Run the child method
        $this->init($app);

        return $this->routes;
    }

    // --------------------------------------------------------------

    /**
     * Initialize method
     *
     * Should load $this->addRoute() for whatever
     * routes to register with the controller and do any other runtime setup
     */
    abstract protected function init(Application $app);

    // --------------------------------------------------------------

    /**
     * Add a route for this controller
     *
     * @param string $path
     * @param string $classMethod
     * @param int|string|array $methods
     */
    protected function addRoute($path, $classMethod, $methods = self::ALL)
    {
        if ( ! method_exists($this, $classMethod)) {
            throw new \InvalidArgumentException(sprintf(
                "Cannot register route '%s' class method '%s' is not callable",
                $path,
                $classMethod
            ));
        }

        if ($methods == self::ALL) {
            $this->routes->match($path, array($this, $classMethod));            
        }
        else {

            if (is_array($methods)) {
                $methods = implode("|", $methods);
            }

            $this->routes->match($path, array($this, $classMethod))->method($methods);
        }
    }

    // --------------------------------------------------------------

    /**
     * Log a message to Monolog
     * 
     * @param string $level
     * @param string $message
     * @param array  $context
     * @param boolean  Whether the record has been processed
     */
    protected function log($level, $message, Array $context = array())
    {
        $method = 'add' . ucfirst(strtolower($level));
        if (method_exists($this->app['monolog'], $method)) {
            return call_user_func(array($this->app['monolog'], $method), $message, $context);
        }
        else {
            throw new \InvalidArgumentException(sprintf(
                "The logging level '%s' is invalid. See Monolog documentation for valid log levels",
                $level
            ));
        }
        
    }

    // --------------------------------------------------------------

    /**
     * Shortcut for debugging
     *
     * @param mixed $item
     * @param string $msg  Optional, if $item is not a string
     */
    protected function debug($item, $msg = null)
    {
        if (is_string($item)) {
            return $this->log('debug', $item);
        }
        else {
            return $this->log('debug', $msg ?: 'Debug', (array) $item);
        }
    }

    // --------------------------------------------------------------

    /**
     * Render
     *
     * Uses twig to render, and also embeds the output in a template if desired
     *
     * @param string $view              View filename (in Views directory)
     * @param string|boolean $template  Template filename (in Views directory) or false
     * @param return string             The rendered output
     */
    protected function render($view, $data = array(), $template = 'base.html.twig')
    {
        //Render in template, or render without template if none specified
        if ($template) {
            $content = $this->app['twig']->render($view, $data);
            $data = array_merge($data, array('__content__' => $content));
            return $this->app['twig']->render($template, $data);
        }
        else {
            return $this->app['twig']->render($view, $data);
        }        
    }

    // --------------------------------------------------------------    

    /**
     * Abort
     *
     * @int $code
     * @string $message
     */
    protected function abort($code, $message)
    {
        $this->app->abort($code, $message);
    }

    // --------------------------------------------------------------

    /**
     * Get query string parameters from input
     *
     * @param string|null $which
     * @return array|mixed|null
     */
    protected function getQueryParams($which = null)
    {
        return ( ! is_null($which))
            ? $this->app['request']->query->get($which)
            : $this->app['request']->query->all();
    }

    // --------------------------------------------------------------

    /**
     * Get post parameters from input
     *
     * @param string|null $which     
     * @return array|mixed|null
     */
    protected function getPostParams($which = null)
    {
        return ( ! is_null($which))
            ? $this->app['request']->request->get($which)
            : $this->app['request']->request->all();        
    }

    // --------------------------------------------------------------

    /**
     * Redirect to another path in the app
     *
     * @param string $path
     * @return  Redirection (halts app and redirects)
     */
    protected function redirect($path)
    {
        //Ensure left slash
        $path = '/' . ltrim($path, '/');

        //Do it
        return $this->app->redirect($this->app['url.app'] . $path);
    } 

    // --------------------------------------------------------------

    /**
     * Get a form
     *
     * @param string $formName       Either fully-quaflified or simple classname
     * @param object|array $data     Object or array to bind the form to
     * @param boolean $bindToRequest Auto-bind if necessary
     */
    protected function getForm($formName, $data = array(), $bindToRequest = true)
    {
        //Check name
        if ( ! class_exists($formName)) {
            $formName = 'Citagora\Web\Form\\' . $formName;
        }

        if ( ! class_exists($formName)) {
            throw new \InvalidArgumentException("The form named: " . $formName . " does not exist");
        }

        $form = new $formName($data = array());
        $form = $this->app['form.factory']->create($form, $data = array());

        if ($this->app['request']->isMethod('POST') && $bindToRequest) {
            $form->bind($this->app['request']);
        }

        return $form;
    }

    // --------------------------------------------------------------

    /**
     * See if a form was submitted
     *
     * @param $form
     */
    protected function formWasSubmitted(Form $form)
    {
        return $form->isBound();
    }       

    // --------------------------------------------------------------

    /**
     * Get the current URL
     *
     * @return string
     */
    protected function getUrl()
    {
        return $this->app['url.current'];
    }

    // --------------------------------------------------------------

    /**
     * Get the current request
     *
     * @return Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->app['request'];
    }

    // --------------------------------------------------------------

    /**
     * Get the current path
     *
     * @return string
     */
    protected function getPath()
    {
        return $this->app['request']->getPathInfo();
    }

    // --------------------------------------------------------------

    /**
     * Access session account manager
     *
     * @return Citagora\Tool\Account
     */
    protected function account()
    {
        return $this->app['account'];
    }

    // --------------------------------------------------------------

    /**
     * Set a notice
     *
     * @param string $notice
     * @param string $level
     * @param string $scope
     */
    protected function setNotice($message, $type = 'info', $scope = 'global')
    {
        $this->app['notices']->add($message, $type, $scope);
    }      

}

/* EOF: Controller.php */