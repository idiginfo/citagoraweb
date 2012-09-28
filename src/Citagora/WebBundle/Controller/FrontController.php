<?php

namespace Citagora\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        return $this->render('CitagoraWebBundle:Front:index.html.twig');
    }
}