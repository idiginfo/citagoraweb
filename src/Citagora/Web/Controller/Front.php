<?php

namespace Citagora\Web\Controller;

use Silex\Application;

class Front extends ControllerAbstract
{
    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        $this->addRoute('/', 'index');
    }
    
    // --------------------------------------------------------------

    public function index()
    {
        return $this->render('Front/index.html.twig');
    }
}

/* EOF: Front.php */