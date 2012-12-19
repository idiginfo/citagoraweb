<?php

namespace Citagora\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Citagora\EntityManager\Entity;
use Illuminate\Hashing\HasherInterface;

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
     * Check a cleartext password to see if it matches this record
     *
     * @param string $password  Cleartext password
     * @return boolean
     */
    public function checkPassword($password)
    {
        var_dump($this->hasher);
        if ($this->hasher) {
            return ($this->hasher->check($password, $this->password));
        }
        else {
            return (strcmp($password, $this->password) == 0);
        }
    }
}

/* EOF: User.php */