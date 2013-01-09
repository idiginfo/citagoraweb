<?php

namespace Citagora\Common\Entity;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Citagora\Common\EntityManager\Entity;
use Illuminate\Hashing\HasherInterface;
use Exception;

/**
 * User Entity
 * @ODM\Document
 */
class User extends Entity
{
    /**
     * @var Illuminate\Hashing\HasherInterface
     */
    private $hasher;

    /** 
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     * @ODM\String
     * @ODM\UniqueIndex
     */
    protected $email;

    /**
     * @var string
     * @ODM\String
     */
    protected $firstName;

    /**
     * @var string
     * @ODM\String
     */
    protected $lastName;

    /**
     * @var string
     * @ODM\String   
     */
    protected $password;

    /**
     * @var array
     * @ODM\Hash
     */
    protected $oauthServices;

    /**
     * @var int
     * @ODM\Int   
     */
    protected $numLogins;

    /**
     * @var string
     * @ODM\String
     */
    protected $resetToken;

    // -------------------------------------------------------------------------

    /**
     * Constructor
     *
     * Initializes the collections per ODM best practices
     * Initilaizes hasher and other variables
     *
     * @param Illuminate\Hashing\HasherInterface $hasher
     */
    public function __construct(HasherInterface $hasher = null)
    {
        $this->setHasher($hasher);

        $this->ownedRecords      = new ArrayCollection;
        $this->subscribedRecords = new ArrayCollection;

        $this->numLogins     = 0;
        $this->oauthServices = array();
    }

    // -------------------------------------------------------------------------

    /**
     * Set the hasher
     *
     * @param Illuminate\Hashing\HasherInterface $hasher
     */
    public function setHasher(HasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    // -------------------------------------------------------------------------

    /**
     * Set magic method overrides its parent by hashing password when set
     */
    public function __set($item, $val)
    {   
        switch ($item) {

            case 'oauthServices':
                throw new Exception("Cannot modify oauthServices property directly.  Use public methods");
            case 'password':
                $val = ($this->hasher) ? $this->hasher->make($val) : $val;
            break;
        }

        parent::__set($item, $val);
    }

    // -------------------------------------------------------------------------

    public function __get($item)
    {
        return parent::__get($item);
    }

    // -------------------------------------------------------------------------

    /**
     * Remove an oauthService
     *
     * @param string $service  Service name
     */
    public function removeOauthService($service)
    {
        if (isset($this->oauthServices[$service])) {
            unset($this->oauthServices[$service]);
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Add or update an OAuth service
     *
     * @param string $service      Service name
     * @param string $id           User ID at the service
     * @param string $accessToken  Optional
     */
    public function setOauthService($service, $id, $accessToken = null)
    {
        $this->oauthServices[$service] = array(
            'id'          => $id, 
            'accessToken' => $accessToken
        );
    }

    // -------------------------------------------------------------------------

    /**
     * Check a cleartext password to see if it matches this record
     *
     * @param string $password  Cleartext password
     * @return boolean
     */
    public function checkPassword($password)
    {
        //Users can never authenticate against empty passwords
        if (empty($password)) {
            return false;
        }

        //If hasher, use that
        if ($this->hasher) {
            return ($this->hasher->check($password, $this->password));
        }
        else {
            return (strcmp($password, $this->password) == 0);
        }
    }
}

/* EOF: User.php */