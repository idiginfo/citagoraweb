<?php

namespace Citagora\Common\BackendAPI;

use Citagora\Common\Model\User\User;

/**
 * User API Interface
 */
interface UserInterface
{
    /**
     * Retrieve user by identifier
     *
     * @param string $identifier  Usually an email or some other unique identifier
     * @return User|boolean       FALSE if no user exists with this identifier
     */
    function getUser($identifier);

    /**
     * Retrieve user by email
     *
     * @param string $email  Email Address
     * @return User|boolean  FALSE if no user exists with this identifier
     */
    function getUserByEmail($email);

    /**
     * Retrieve multiple users by identifier
     *
     * @param  array  Array of unique identifiers
     * @return array  Array of User objects
     */
    function getUsers(array $identifiers);

    /**
     * Check user by email and password
     *
     * @param  string $email
     * @param  string $password   Cleartext password
     * @return null|boolean|User  Returns the User object if success, null if not found
     *                            or false, if incorrect password
     */
    function checkCredentials($email, $password);

    /**
     * Create a new user
     *
     * @param array $attributes  Optionally specify attributes upon construction
     * @return User
     */
    function createNewUser(array $attributes = array());

    /**
     * Save a user
     *
     * @param Citagora\Common\Model\User\User $user
     */
    function saveUser(User $user);

}

/* EOF: UserInterface.php */