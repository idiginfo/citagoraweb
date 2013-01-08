<?php

namespace Citagora\Web\Controller;

use Silex\Application;

/**
 * Users Controller
 */
class Users extends ControllerAbstract
{
    /**
     * @var Citagora\EntityManager\Collection
     */
    private $users;

    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        $this->addRoute('/login',           'login');
        $this->addRoute('/login/{service}', 'svclogin');
        $this->addRoute('/logout',          'logout');
        $this->addRoute('/account',         'account');
        $this->addRoute('/forgot',          'forgot');
        $this->addRoute('/register',        'register');    

        $this->users = $this->getEntityCollection('User');
    }

    // --------------------------------------------------------------

    public function login()
    {
        //Build a search form
        $loginForm = $this->getForm('UserLogin');

        //If the form was submitted, process it...
        if ($this->formWasSubmitted($loginForm)) {

            $creds = $loginForm->getData();

            //If credentials match, then login
            if ($this->users->checkCredentials($creds['email'], $creds['password'])) {

                $this->debug("LOGGED IN!");

            }
            else { //if not, then display notice

                $this->setNotice('');

            }

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