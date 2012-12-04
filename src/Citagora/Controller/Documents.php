<?php

namespace Citagora\Controller;
use Citagora\Model\DocumentSearchRequest;

/**
 * Documents Controller
 */
class Documents extends Controller
{
    // --------------------------------------------------------------

    protected function init()
    {
        $this->addRoute('/documents/',            'index');
        $this->addRoute('/documents/{id}/',       'single');
        $this->addRoute('/documents/{id}/{sub}/', 'single');
        $this->addRoute('/search/',            'search');
        $this->addRoute('/search/{query}/',    'search');
    }

    // --------------------------------------------------------------

    public function index()
    {
        return $this->render('Documents/index.html.twig');
    }

    // --------------------------------------------------------------

    public function single($id, $subinfo = null)
    {
        return $this->render('Documents/single.html.twig');
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