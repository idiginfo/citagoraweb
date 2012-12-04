<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;

class Contributor extends AbstractValueObject
{
    /**
     * @var string
     */
    protected $givenname;

    /**
     * @var string
     */
    protected $surname;

    /**
     * @var string
     */
    protected $type;

    // --------------------------------------------------------------

    public function __tostring()
    {
        $gn = ($this->givenname)
            ? ', ' . $this->givenname
            : null;

        return $this->surname . $gn;
    }
}


/* EOF: Contributor.php */