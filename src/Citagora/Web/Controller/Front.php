<?php

namespace Citagora\Web\Controller;

use Silex\Application;

class Front extends ControllerAbstract
{
    // --------------------------------------------------------------

    protected function loadRoutes()
    {
        $this->addRoute('/', 'index');
    }

    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        //nothing yet
    }
    
    // --------------------------------------------------------------

    public function index()
    {
        return $this->render('Front/index.html.twig');
    }
}

/* EOF: Front.php */