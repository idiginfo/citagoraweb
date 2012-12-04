<?php

namespace Citagora\MongoDoc;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class User extends MongoDoc
{
    /** 
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     * @ODM\String
     * @Index
     */
    protected $email;

    /**
     * @var string
     * @ODM\String   
     */
    protected $password;

    /**
     * @var array
     * @ODM\Collection(strategy="set")
     */
    protected $oauthServices;

    /**
     * @var int
     * @ODM\Int   
     */
    protected $numLogins;
}

/* EOF: User.php */