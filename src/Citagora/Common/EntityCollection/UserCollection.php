<?php

namespace Citagora\Common\EntityCollection;

use Illuminate\Hashing\HasherInterface;
use Citagora\Common\EntityManager\Collection as EntityCollection;
use Citagora\Common\Entity\User;

/**
 * Manages users in the database
 */
class UserCollection extends EntityCollection
{
    /**
     * @var Illuminate\Hashing\HasherInterface
     */
    private $hasher;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Illuminate\Hashing\HasherInterface $hasher
     */
    public function __construct(HasherInterface $hasher)
    {
        $this->setHasher($hasher);
    }

    // --------------------------------------------------------------

    /**
     * Set password hasher
     *
     * @param Illuminate\Hashing\HasherInterface
     */
    public function setHasher(HasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // --------------------------------------------------------------
    
    /**
     * Event manager callback on postLoad for an object
     *
     * @param DL2SL\Entity\User
     */
    public function postLoadEntity(User $user)
    {
        if ($this->hasher) {
            $user->setHasher($this->hasher);
        }
    }
    
    // --------------------------------------------------------------

    /** 
     * Override default factory method by including hasher
     *
     * @inherit 
     */
    public function factory($params = array())
    {
        return parent::factory(array($this->hasher));
    }

    // --------------------------------------------------------------

    /**
     * Check user via email/password
     *
     * @param string $email
     * @return User|boolean  False if not matched
     */
    public function getUserByEmail($email)
    {
        return $this->findOneBy(array('email' => $email));
    }

    // --------------------------------------------------------------

    /**
     * Check user via email/password
     *
     * @param string $email
     * @return User|null  Null if not matched
     */
    public function getUserByOAuth($service, $id)
    {
        return $this->findOneBy(array('oauth.{$service}' => $id));
    }

    // --------------------------------------------------------------

    /**
     * Check user via email/password
     *
     * @param string $email
     * @return User|null  Null if not matched
     */
    public function getUserByResetToken($token)
    {
        return $this->findOneBy(array('resetToken' => $token));       
    }


    // --------------------------------------------------------------

    /**
     * Check user via email/password
     *
     * @param string $email
     * @param string $password
     * @return User|boolean  False if not matched
     */
    public function checkCredentials($email, $password)
    {
        $rec = $this->getUserByEmail($email);

        //If record, and password check works, return the user object, else false
        return ($rec && $rec->checkPassword($password)) ? $rec : false;
    }
}

/* EOF: UserCollection.php */