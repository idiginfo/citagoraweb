<?php

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
     * Retrieve multiple users by identifier
     *
     * @param  array  Array of unique identifiers
     * @return array  Array of User objects
     */
    function getUsers(array $identifiers);

    /**
     * Check user by identifier and password
     *
     * @param  string $identifier
     * @param  string $password   Cleartext password
     * @return null|boolean|User  Returns the User object if success, null if not found
     *                            or false, if incorrect password
     */
    function checkCredentials($identifier, $password);


    /**
     * Create a new user
     *
     * @param array $attributes  Optionally specify attributes upon construction
     * @return User
     */
    function createNewUser(array $attributes = array());

}

/* EOF: UserInterface.php */