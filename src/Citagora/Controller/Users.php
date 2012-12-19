<?php

namespace Citagora\Controller;

class Users extends ControllerAbstract
{
    // --------------------------------------------------------------

    protected function init()
    {
        $this->addRoute('/login',           'login');
        $this->addRoute('/login/{service}', 'svclogin');
        $this->addRoute('/logout',          'logout');
        $this->addRoute('/account',         'account');
        $this->addRoute('/forgot',          'forgot');
        $this->addRoute('/register',        'register');        
    }

    // --------------------------------------------------------------

    public function login()
    {
        //Build a search form
        $loginForm = $this->getForm('UserLogin');

        //If the form was submitted, process it...
        if ($this->formWasSubmitted($loginForm)) {

            $this->log('debug', 'Form was submitted!', $this->getPostParams());

        }

        //Data to pass to the view
        $data = array(
            'loginForm' => $loginForm->createView()
        );

        //Render the view
        return $this->render('Users/login.html.twig', $data);
    }

    // --------------------------------------------------------------

    public function svclogin($service)
    {
        //Do service login here...
    }

    // --------------------------------------------------------------

    public function logout()
    {
        return $this->render('Users/logout.html.twig');
    }

    // --------------------------------------------------------------

    public function account()
    {
        return $this->render('Users/account.html.twig');
    }

    // --------------------------------------------------------------

    public function forgot()
    {
        return $this->render('Users/forgot.html.twig');
    }

    // --------------------------------------------------------------

    public function register()
    {
        return $this->render('Users/register.html.twig');        
    }

}

/* EOF: Users.php */