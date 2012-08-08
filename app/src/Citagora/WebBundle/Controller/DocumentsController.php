<?php

namespace Citagora\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentsController extends Controller
{
    public function searchAction($query = null)
    {
        return $this->render('CitagoraWebBundle:Documents:search.html.twig');
    }

    public function paperAction($id = null, $subinfo = null)
    {
        return $this->render('CitagoraWebBundle:Documents:paper.html.twig');        
    }
}