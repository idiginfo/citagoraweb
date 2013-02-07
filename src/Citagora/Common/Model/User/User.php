<?php

class User extends Model
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected $oauthServices;

    /**
     * @var int
     */
    protected $numLogins;

    /**
     * @var string
     */
    protected $resetToken;
}

/* EOF: User */