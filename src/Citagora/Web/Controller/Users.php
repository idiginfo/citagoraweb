<?php

namespace Citagora\Web\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Citagora\Common\Entity\User;

/**
 * Users Controller
 */
class Users extends ControllerAbstract
{
    /**
     * @var Citagora\EntityManager\Collection
     */
    private $users;

    /**
     * @var Pimple
     */
    private $oauthProviders;

    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        $this->addRoute('/login',             'login');
        $this->addRoute('/login/{service}',   'svclogin');
        $this->addRoute('/logout',            'logout');
        $this->addRoute('/account',           'accountinfo');
        $this->addRoute('/forgot',            'forgot');

        $this->users = $app['em']->getCollection('User');
        $this->oauthProviders = $app['oauth_clients'];
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
                $this->setNotice('Invalid email or password.  Please try again', 'bad');
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

    /**
     * Login via a given service, or connect a logged-in account
     * with a service
     *
     * @param string $service  The service name
     */
    public function svclogin($service)
    {
        //If cannot find service provider, 404
        if ( ! $this->oauthProviders->offsetExists($service)) {
            $this->abort(404, "Login for {$service} does not exist");    
        }

        //Some variables
        $client      = $this->oauthProviders[$service];
        $callbackUrl = $this->getUrl();

        //If no $_GET['code'], then we'll need to get the request token
        if ( ! $this->getQueryParams('code')) {
            //Redirect to the service
            return new RedirectResponse($client->getAuthUrl($callbackUrl));
        }

        //If $_GET['code'], then we'll get the info
        $accessToken = $client->getAccessToken($this->getRequest());
        $info = $client->getUserInfo($accessToken);

        //Connect the service to the account if logged-in
        if ($this->account()->isLoggedIn()) {

            $user = $this->account()->getUser();
            $user->setOauthService($service, $info->get('id'), $accessToken);
            $this->setNotice("Succesfully connected your account with " . ucfirst($service));
            return $this->redirect('account');
        }
        else {

            //Check for existing user
            $existingUser = $this->users->getUserByEmail($info->get('email'));

            if ($existingUser) {

                $user = $existingUser;
                $user->setOauthService($service, $info->get('id'), $accessToken);
            }
            else {

                $user = $this->users->factory();

                //Add info for new user
                $user->setOauthService($service, $info->get('id'), $accessToken);
                $user->firstName = $info->get('firstName');
                $user->lastName  = $info->get('lastName');
                $user->email     = $info->get('email');
            }

            return $this->processLogin($user);
        }

    }

    // --------------------------------------------------------------

    /**
     * Logout
     */
    public function logout()
    {
        //Logout
        if ($this->account()->isLoggedIn()) {
            $this->account()->logout();
            $this->setNotice("You have been logged-out.  Goodbye!");
        }

        return $this->redirect('/');
    }

    // --------------------------------------------------------------

    /**
     * Account Management
     */
    public function accountinfo()
    {
        //Redirect to login page
        if ( ! $this->account()->isLoggedIn()) {
            return $this->redirect('/login');
        }

        //Will add form processing stuff here

        //Get the user and show the account page
        $data['user'] = $this->account()->getUser();
        
        return $this->render('Users/account.html.twig', $data);
    }

    // --------------------------------------------------------------

    public function forgot()
    {
        return $this->render('Users/forgot.html.twig');
    }

    // --------------------------------------------------------------

    /**
     * Process a login
     *
     * Make decisions about where to redirect and what-not here
     */
    private function processLogin(User $user)
    {
        //Do the login
        $this->account()->login($user);
        return $this->redirect('/dashboard');
    }
}

/* EOF: Users.php */