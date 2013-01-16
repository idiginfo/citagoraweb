<?php

namespace Citagora\Web\Oauth;

use Illuminate\Socialite\UserData;
use Symfony\Component\HttpFoundation\ParameterBag;

class UserInfo
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * Mappings is an array with ['id', 'email', 'firstName', 'lastName'] as keys
     *
     * @param UserData $userdata
     * @param array    $mappings
     */
    public function __construct(UserData $userdata, array $mappings)
    {
        $this->id        = $userdata->get($mappings['id']);
        $this->email     = $userdata->get($mappings['email']);
        $this->firstName = $userdata->get($mappings['firstName']);
        $this->lastName  = $userdata->get($mappings['lastName']);
    }

    // --------------------------------------------------------------

    /**
     * @return string
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    // --------------------------------------------------------------

    /**
     * Get a value
     *
     * @return string
     */
    public function get($value)
    {
        return $this->$value;
    }
}

/* EOF: UserInfo.php */