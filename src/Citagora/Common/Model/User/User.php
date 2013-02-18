<?php

namespace Citagora\Common\Model\User;

use Citagora\Common\Model\Model;

class User extends Model
{
    /**
     * @var string
     * @Attribute
     */
    protected $email;

    /**
     * @var string
     * @Attribute
     */
    protected $firstName;

    /**
     * @var string
     * @Attribute
     */
    protected $lastName;

    /**
     * @var string
     * @Attribute
     */
    protected $password;

    /**
     * @var array
     * @Attribute
     */
    protected $oauthServices;

    /**
     * @var int
     * @Attribute
     */
    protected $numLogins;

    /**
     * @var string
     * @Attribute
     */
    protected $resetToken;
}

/* EOF: User */