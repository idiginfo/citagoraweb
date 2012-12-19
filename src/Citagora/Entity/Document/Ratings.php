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
     * @ODM\Float     
     */
    protected $overall;

    /**
     * @var float  Readability summary
     * @ODM\Float     
     */
    protected $readability;

    /**
     * @var float  Accuracy summary
     * @ODM\Float     
     */
    protected $accuracy;

    /**
     * @var float  Originality summary
     * @ODM\Float     
     */
    protected $orginiality;

    /**
     * @var int  Total number of ratings given
     * @ODM\Int
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