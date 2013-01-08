<?php

namespace Citagora\Web\Controller;

use Citagora\Web\Model\DocumentSearchRequest;
use Silex\Application;

/**
 * User dashboard controller
 */
class Dashboard extends ControllerAbstract
{
    /**
     * @var Citagora\EntityCollection\DocumentCollection
     */
    private $documentCollection;

    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        $this->addRoute('/dashboard', 'index');

        $this->documentCollection = $app['em']->getCollection('Document\Document');
    }

    // --------------------------------------------------------------

    public function index()
    {
        //Data to send
        $data = array();

        return $this->render('Dashboard/index.html.twig', $data);            
    }
}

/* EOF: Dashboard.php */