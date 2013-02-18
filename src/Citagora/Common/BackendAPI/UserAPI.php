<?php

namespace Citagora\Common\BackendAPI;

use Citagora\Common\DataSource\Mongo\EntityCollection\UserCollection;
use Citagora\Common\Model\User\User;

/**
 * Default User API -- Provides an abstract interface to user functionality
 *
 * Uses the Mongo Datasource Entity Library to manage users
 */
class UserAPI implements UserInterface
{
    /**
     * @var Citagora\Common\DataSource\Mongo\EntityCollection\UserCollection
     */
    private $userCollection;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Citagora\Common\DataSource\Mongo\EntityCollection\UserCollection
     */
    public function __construct(UserCollection $userCollection)
    {
        $this->userCollection = $userCollection;
    }

    // --------------------------------------------------------------

    /**
     * Retrieve user by id
     *
     * @param string $identifier  Unique identifier
     * @return User|boolean       FALSE if no user exists with this identifier
     */
    public function getUser($identifier)
    {
        return $this->userCollection->find($identifier);
    }

    // --------------------------------------------------------------

    /**
     * Retrieve user by email
     *
     * @param string $email  Email Address
     * @return User|boolean  FALSE if no user exists with this identifier
     */
    public function getUserByEmail($email)
    {
        return $this->userCollection->getUserByEmail($email);
    }

    // --------------------------------------------------------------

    /**
     * Retrieve multiple users by identifier
     *
     * @param  array  Array of unique identifiers
     * @return array  Array of User objects; keys are ids
     */
    function getUsers(array $identifiers)
    {
        $arr = array();
        foreach($identifiers as $id) {
            $arr[$id] = $this->getUser($id);
        }

        return $arr;
    }

    // --------------------------------------------------------------

    /**
     * Check user by email and password
     *
     * @param  string $email
     * @param  string $password   Cleartext password
     * @return null|boolean|User  Returns the User object if success, null if not found
     *                            or false, if incorrect password
     */
    function checkCredentials($email, $password)
    {
        if ($this->getUser($identifier)) {
            return $this->userCollection->checkCredentials($identifier, $password);
        }
        else {
            return null;
        }
    }

    // --------------------------------------------------------------

    /**
     * Create a new user
     *
     * @param array $attributes  Optionally specify attributes upon construction
     * @return User
     */
    function createNewUser(array $attributes = array())
    {
        return $this->userCollection->factory($attributes);
    }

    // --------------------------------------------------------------

    /**
     * Save a user
     *
     * @param Citagora\Common\Model\User\User
     */
    function saveUser(User $user)
    {
        return $this->userCollection->save($user);
    }
}

/* EOF: User.php */