
<?php
// src/CitagoraWebBundle/Entities/User.php

namespace Citagora\WebBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class SavedSearch
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @String
     */
    protected $searchQuery;

    /**
     * @ReferenceOne(targetDocument="User")
     */
    protected $userID;

    // --------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        //Logic heres
    }
}