<?php

namespace Citagora\Tool;

use Symfony\Component\HttpFoundation\Session\Session;
use Citagora\EntityCollection\UserCollection;
use Citagora\Entity\User;

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
     * @var Citagora\EntityCollection\UserCollection $userCollection
     */
    private $userCollection;

    /**
     * @var Citagora\Entity\User
     */
    private $user;

    // --------------------------------------------------------------

    /**
     * Constructor
     * 
     * @param Symfony\Component\HttpFoundation\Session\Session
     * @param Citagora\EntityCollection\UserCollection $userColl
     */
    public function __construct(Session $session, UserCollection $userColl)
    {
        $this->session        = $session;
        $this->userCollection = $userColl;
    }

    // --------------------------------------------------------------

    /**
     * @param Citagora\Entity\User
     */
    public function login(User $user)
    {
        $user->numLogins++;
        $this->userCollection->save($user);

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

    private function buildUserFromSession()
    {
        $id = $this->sesion->get('user', false);

        if ($id) {
            $this->user = $this->userCollection->find($id);
        }
    }
}

/* EOF: Account.php */