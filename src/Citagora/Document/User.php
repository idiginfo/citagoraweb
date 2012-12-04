<?php

namespace Citagora\Document;

/**
 * @ODM\Document
 */
class User extends AbstractDocument
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