<?php

namespace Citagora\Entity\Document;
use Citagora\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use InvalidArgumentException;

/**
 * @ODM\EmbeddedDocument
 */
class Ratings extends Entity
{
    /**
     * @param float  Overall rating summary
     */
    protected $overall;

    /**
     * @var float  Readability summary
     */
    protected $readability;

    /**
     * @var float  Accuracy summary
     */
    protected $accuracy;

    /**
     * @var float  Originality summary
     */
    protected $orginiality;

    /**
     * @var int  Total number of ratings given
     */
    protected $totalCount;

    // --------------------------------------------------------------

    public function __set($item, $value)
    {
        if ( ! is_numeric($value)) {
            throw new InvalidArgumentException("The {$item} must be numeric");
        }

        $this->$item = (float) $value;
    }
}

/* EOF: Ratings.php */