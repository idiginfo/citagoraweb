<?php

namespace Citagora\Controller;

use Citagora\Model\DocumentSearchRequest;
use Citagora\Service\Documents as DocumentsService;
use Silex\Application;

/**
 * Documents Controller
 */
class Documents extends ControllerAbstract
{
    /**
     * @var Citagora\EntityCollection\DocumentCollection
     */
    private $documentCollection;

    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        $this->addRoute('/documents/',            'index');
        $this->addRoute('/documents/{id}/',       'single');
        $this->addRoute('/documents/{id}/{sub}/', 'single');
        $this->addRoute('/search/',               'search');
        $this->addRoute('/search/{query}/',       'search');

        $this->documentCollection = $this->getEntityCollection('Document\Document');
    }

    // --------------------------------------------------------------

    public function index()
    {
        //Get some documents
        $data['docs'] = $this->documentCollection->getLatestDocuments(10);

        return $this->render('Documents/index.html.twig', $data);
    }

    // --------------------------------------------------------------

    public function single($id, $subinfo = null)
    {
        $data['document'] = $this->documentCollection->find($id);
        return $this->render('Documents/single.html.twig', $data);
    }

    // --------------------------------------------------------------

    public function search($query = null)
    {
        //Build a search form
        $searchForm = $this->getForm('SearchBasic', new DocumentSearchRequest());

        //If the form was submitted, process it...
        if ($this->formWasSubmitted($searchForm)) {

            $this->log('debug', 'Form was submitted!', $this->getPostParams());

        }

        //Data to pass to the view
        $data = array(
            'searchForm' => $searchForm->createView()
        );

        //Render the view
        return $this->render('Documents/search.html.twig', $data);
    }

    // --------------------------------------------------------------
}

/* EOF: Documents.php */