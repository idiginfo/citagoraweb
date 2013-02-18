<?php

namespace Citagora\Common\DataSource\Mongo\Entity;

use Citagora\Common\Model\User\User as UserModel;
use Citagora\Common\DataSource\Mongo\EntityManager\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Illuminate\Hashing\HasherInterface;
use Illuminate\Socialite\OAuthTwo\AccessToken;
use Exception;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Citagora\Common\Annotations\Attribute;

/**
 * User Entity
 * @ODM\Document
 */
class User extends UserModel implements Entity
{
    /**
     * @var Illuminate\Hashing\HasherInterface
     */
    private $hasher;

    /**
     * @ODM\Id
     * @Attribute
     */
    protected $id;

    /**
     * @var string
     * @ODM\String
     * @ODM\UniqueIndex
     * @Attribute
     */
    protected $email;

    /**
     * @var string
     * @ODM\String
     * @Attribute
     */
    protected $firstName;

    /**
     * @var string
     * @ODM\String
     * @Attribute
     */
    protected $lastName;

    /**
     * @var string
     * @ODM\String
     * @Attribute
     */
    protected $password;

    /**
     * @var array
     * @ODM\Hash
     * @Attribute
     */
    protected $oauthServices;

    /**
     * @var int
     * @ODM\Int
     * @Attribute
     */
    protected $numLogins;

    /**
     * @var string
     * @ODM\String
     * @Attribute
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
    public function setOauthService($service, $id, AccessToken $accessToken = null)
    {
        $this->oauthServices[$service] = array(
            'id'          => $id,
            'accessToken' => ($accessToken) ? $accessToken->getValue() : null
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