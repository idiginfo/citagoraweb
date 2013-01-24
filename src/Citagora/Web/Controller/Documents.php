<?php

namespace Citagora\Web\Controller;

use Citagora\Web\Model\DocumentSearchRequest;
use Silex\Application;

/**
 * Documents Controller
 */
class Documents extends ControllerAbstract
{
    /**
     * @var Citagora\Common\EntityCollection\DocumentCollection
     */
    private $documentCollection;

    /**
     * @var Citagora\Common\EntityManager\GenericCollection
     */
    private $reviewCollection;

    /**
     * @var Citagora\Common\Tool\DocumentFactory
     */
    private $documentFactory;

    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        //Add routes
        $this->addRoute('/documents/',            'index');
        $this->addRoute('/documents/{id}/',       'single');
        $this->addRoute('/documents/{id}/{sub}/', 'single');
        $this->addRoute('/documents/rate/{id}',   'rate', 'post');

        $this->addRoute('/search/',               'search');
        $this->addRoute('/search/{query}/',       'search');

        //Get collections
        $this->documentCollection = $app['em']->getCollection('Document\Document');
        $this->reviewCollection   = $app['em']->getCollection('Document\Review');
        $this->documentFactory    = $app['document_factory'];
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

    /**
     * AJAX: Add a rating to a document
     *
     * TODO: Debug this!
     *
     * @param string $id  Document ID
     */
    public function rate($id)
    {
        //Ensure logged-in
        if ( ! $this->account()->isLoggedIn()) {
            return $this->abort(401, 'You must login to rate documents');
        }

        //Get the category and value from POST (both are required)
        $category = $this->getPostParams('category');
        $value    = $this->getPostParams('value');

        //Ensure required parameters
        if ( ! $category OR ! $value) {
            return $this->abort(400, 'Invalid parameters sent');
        }

        //Get the document to rate and the user
        $doc  = $this->documentCollection->find($id);
        $user = $this->account()->getUser();

        //Ensure document exists
        if ( ! $doc) {
            return $this->abort(404, 'Document not found');
        }

        //See if a review exists for this document and user
        $reviewObj = $this->reviewCollection->getUserReview($doc, $user);

        //Else create a new one...
        if ( ! $reviewObj) {
            $reviewObj = $this->reviewCollection->factory($doc, $user);
        }

        $reviewObj->addRating($category, $value);
        $this->reviewCollection->save($reviewObj, $this->account()->getUser());

        //Return JSON
        return $this->json(array('success' => true));
    }    
}

/* EOF: Documents.php */