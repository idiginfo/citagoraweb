<?php

namespace Citagora\Common\Entity\Document;

use Citagora\Common\Entity\User;
use Exception, InvalidArgumentException;

class Review extends Model
{
    /**
     * @var arrray  Definition of rating categories 
     *              (keys are categories, values are human-readable)
     */
    private static $ratingCategories = array(
        'overall'     => 'Overall',
        'readability'  => 'Readability',
        'accuracy'    => 'Accuracy',
        'originality' => 'Originality'
    );

    // --------------------------------------------------------------

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $ratings;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Document
     *    targetDocument="Document",
     *    inversedBy="reviews",
     *    cascade={"persist","refresh","merge"}
     * )
     */
    protected $document;

    // --------------------------------------------------------------

    public function __construct(Document $document, User $user = null)
    {
        $this->document = $document;
        $this->user     = $user;
        $this->ratings  = array();
    }

    // --------------------------------------------------------------

    public function __set($item, $value)
    {
        switch ($item) {
            case 'ratings':
            case 'document':
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