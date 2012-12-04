<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;
use InvalidArgumentException;

class Ratings extends AbstractValueObject
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