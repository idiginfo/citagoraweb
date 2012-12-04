<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;
use InvalidArgumentException;

class Ratings extends AbstractValueObject
{
    /**
     * @param float  Overall rating summary
     */
    private $overall;

    /**
     * @var float  Readability summary
     */
    private $readability;

    /**
     * @var float  Accuracy summary
     */
    private $accuracy;

    /**
     * @var float  Originality summary
     */
    private $orginiality;

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