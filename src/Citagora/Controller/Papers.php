<?php

namespace Citagora\Controller;
use Citagora\Model\PaperSearchRequest;

class Papers extends Controller
{
    // --------------------------------------------------------------

    protected function init()
    {
        $this->addRoute('/papers/',            'index');
        $this->addRoute('/papers/{id}/',       'single');
        $this->addRoute('/papers/{id}/{sub}/', 'single');
        $this->addRoute('/search/',            'search');
        $this->addRoute('/search/{query}/',    'search');
    }

    // --------------------------------------------------------------

    public function index()
    {
        return $this->render('Papers/index.html.twig');
    }

    // --------------------------------------------------------------

    public function single($id, $subinfo = null)
    {
        return $this->render('Papers/single.html.twig');
    }

    // --------------------------------------------------------------

    public function search($query = null)
    {
        //Build a search form
        $searchForm = $this->getForm('SearchBasic', new PaperSearchRequest());

        //If the form was submitted, process it...
        if ($this->formWasSubmitted($searchForm)) {

            $this->log('debug', 'Form was submitted!', $this->getPostParams());

        }

        //Data to pass to the view
        $data = array(
            'searchForm' => $searchForm->createView()
        );

        //Render the view
        return $this->render('Papers/search.html.twig', $data);
    }

    // --------------------------------------------------------------
}

/* EOF: Papers.php */