<?php

namespace Citagora\Controller;

class Front extends ControllerAbstract
{
    // --------------------------------------------------------------

    protected function init()
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