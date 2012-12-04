<?php

namespace Citagora\Controller;

class Users extends Controller
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
        return $this->render('Users/login.html.twig');
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