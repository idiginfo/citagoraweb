<?php

namespace Citagora\Common\Entity\Document;

use Citagora\Common\EntityManager\Entity;
use Citagora\Common\Entity\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Exception, InvalidArgumentException;

/**
 * @ODM\Document(collection="DocumentReview")
 */
class Review extends Entity
{
    /**
     * @var arrray  Definition of rating categories 
     *              (keys are categories, values are human-readable)
     */
    private static $ratingCategories = array(
        'overall'     => 'Overall',
        'readabilty'  => 'Readability',
        'accuracy'    => 'Accuracy',
        'originality' => 'Originality'
    );

    // --------------------------------------------------------------

    /**
     * @var int
     * @ODM\Id     
     */
    protected $id;

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
     * Get valid rating categories
     *
     * @return array  Keys are rating caetegory, values are human names
     */
    public static function getRatingCategories()
    {
        return self::$ratingCategories;
    }

    // --------------------------------------------------------------

    /**
     * Get a rating
     */
    public function getRating($category)
    {
        if ( ! isset(self::$ratingCategories[$category])) {
            throw new InvalidArgumentException("Rating category {$category} is not valid");
        }

        return isset($this->ratings[$category]) ? $this->ratings[$category] : null;
    }

    // --------------------------------------------------------------

    /**
     * Add a rating
     *
     * @param string $category
     * @param float  $rating
     */
    public function addRating($category, $rating)
    {
        if ( ! isset(self::$ratingCategories[$category])) {
            throw new InvalidArgumentException("Rating category {$category} is not valid");
        }
        if ( ! is_numeric($rating)) {
            throw new InvalidArgumentException("The {$category} rating must be numeric");
        }

        $this->ratings[$category] = (float) $rating;
    }
}

/* EOF: Ratings.php */