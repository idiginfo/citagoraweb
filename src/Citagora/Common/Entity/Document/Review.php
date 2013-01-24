<?php

namespace Citagora\Common\Entity\Document;

use Citagora\Common\EntityManager\Entity;
use Citagora\Common\Entity\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Exception, InvalidArgumentException;

/**
 * @ODM\EmbeddedDocument
 */
class Review extends Entity
{
    /**
     * @var arrray  Definition of rating types
     */
    private $ratingTypes = array(
        'overall'     => 'Overall'
        'readabilty'  => 'Readability',
        'accuracy'    => 'Accuracy',
        'originality' => 'Originality'
    );

    /**
     * @var array
     * @ODM\Hash
     */
    protected $ratings;

    /**
     * @var User
     * @ODM\ReferenceOne(targetDocument="User")
     */
    protected $user;

    // --------------------------------------------------------------

    public function __construct()
    {
        $this->ratings = array();
    }

    // --------------------------------------------------------------

    public function __set($item, $value)
    {
        switch ($item) {
            case 'ratings':
                throw new Exception("Cannot modify {$item} property directly");
            break;
        }

        parent::set($item, $value);
    }

    // --------------------------------------------------------------

    /**
     * Get valid rating types
     *
     * @return array  Keys are rating type, values are human names
     */
    public function getRatingTypes()
    {
        return $this->ratingTypes;
    }

    // --------------------------------------------------------------

    /**
     * Add a rating
     */
    public function addRating($type, $rating)
    {
        if ( ! isset($this->ratingTypes[$type])) {
            throw new InvalidArgumentException("Rating type {$type} is not valid");
        }
        if ( ! is_numeric($rating)) {
            throw new InvalidArgumentException("The rating type {$type} must be numeric");
        }

        $this->ratings[$type] = (float) $rating;
    }
}

/* EOF: Ratings.php */