<?php

namespace Citagora\Common\Model\User;

use Citagora\Common\Model;

class User extends Model
{
    /**
     * @var string
     * @attribute
     */
    protected $email;

    /**
     * @var string
     * @attribute
     */
    protected $firstName;

    /**
     * @var string
     * @attribute
     */
    protected $lastName;

    /**
     * @var string
     * @attribute
     */
    protected $password;

    /**
     * @var array
     * @attribute
     */
    protected $oauthServices;

    /**
     * @var int
     * @attribute
     */
    protected $numLogins;

    /**
     * @var string
     * @attribute
     */
    protected $resetToken;
}

/* EOF: User */