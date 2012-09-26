<?php

namespace Citagora\WebBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Citagora\WebBundle\Model\SearchRequest;

/**
 * Documents Controller
 */
class DocumentsController extends Controller
{
    // --------------------------------------------------------------

    /**
     * Search Action
     *
     * If a query string is provided via POST['query'], that
     * will take preference over a query string sent via parameter
     *
     * @param Request $request The Symfony request object
     * @param string $query An optional formatted query string
     */
    public function searchAction(Request $request, $query = null)
    {
        //If form was submitted, attempt to build a query
        $queryString = $request->request->get('query') ?: $query;

        if ($queryString) {

            $searchReq = $this->get('citagora_web.search_request');
            $searchStrings = $searchReq->parse($queryString);
            var_dump($searchStrings); die();
        }

        //Render the view
        return $this->render('CitagoraWebBundle:Documents:search.html.twig');
    }

    // --------------------------------------------------------------

    /**
     * Paper details action
     *
     * @param string $id
     * @param string $subinfo
     */
    public function paperAction($id = null, $subinfo = null)
    {
        return $this->render('CitagoraWebBundle:Documents:paper.html.twig');        
    }
}