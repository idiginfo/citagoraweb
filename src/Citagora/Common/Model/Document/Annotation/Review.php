<?php

namespace Citagora\Common\Model\Document\Annotation;

use Exception, InvalidArgumentException;
use Citagora\Common\Model\Document\Annotation as AnnotationModel;

class Review extends AnnotationModel
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
     * @attribute
     */
    protected $id;

    /**
     * @var array
     * @attribute
     */
    protected $ratings;

    /**
     * @var User
     * @attribute
     */
    protected $user;

    /**
     * @var Document
     * @attribute
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

/* EOF: Review.php */