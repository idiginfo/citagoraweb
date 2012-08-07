<?php

namespace Citagora\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CitagoraWebBundle:Hello:index.html.twig', array('name' => $name));
    }
}