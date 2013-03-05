<?php

namespace Citagora\Web\Library;

use Symfony\Component\HttpFoundation\Session\Session;
use Citagora\Common\BackendAPI\UserInterface;
use Citagora\Common\Model\User\User;

/**
 * Manages the user in the session
 */
class Account
{
    /**
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * @var Citagora\Common\BackendAPI\UserInterface
     */
    private $userApi;

    /**
     * @var Citagora\Common\Entity\User
     */
    private $user;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Symfony\Component\HttpFoundation\Session\Session
     * @param Citagora\Common\BackendAPI\UserInterface $userApi
     */
    public function __construct(Session $session, UserInterface $userApi)
    {
        $this->session = $session;
        $this->userApi = $userApi;

        $this->buildUserFromSession();
    }

    // --------------------------------------------------------------

    /**
     * @param Citagora\Entity\User
     */
    public function login(User $user)
    {
        $user->numLogins++;
        $this->userApi->saveUser($user);

        $this->user = $user;
        $this->session->set('user', $user->id);
    }

    // --------------------------------------------------------------

    /**
     * @return boolean
     */
    public function logout()
    {
        if ($this->session->get('user', false)) {
            $this->session->remove('user');
            return true;
        }
        else {
            return false;
        }
    }

    // --------------------------------------------------------------

    /**
     * @return boolean
     */
    public function isLoggedIn()
    {
        return (boolean) $this->session->get('user', false);
    }

    // --------------------------------------------------------------

    /**
     * Get specific information about the current user
     *
     * @param string $which
     * @return mixed
     */
    public function info($which)
    {
        return (isset($this->user->$which))
            ? $this->user->$which
            : null;
    }

    // --------------------------------------------------------------

    /**
     * Get the user object
     *
     * @return Citagora\Common\Entity\User;
     */
    public function getUser()
    {
        return $this->user;
    }

    // --------------------------------------------------------------

    /**
     * Load the user object in from the session, if it exists
     *
     * Gets the id from the session and then builds the user object
     * from the User API
     */
    private function buildUserFromSession()
    {
        $id = $this->session->get('user', false);

        if ($id) {
            $this->user = $this->userApi->getUser($id);
        }
    }
}

/* EOF: Account.php */