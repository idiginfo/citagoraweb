<?php

namespace Citagora\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CitagoraWebBundle:Default:index.html.twig', array('name' => $name));
    }
}
