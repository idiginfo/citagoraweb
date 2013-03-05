<?php

namespace Citagora\Common\Model\Document;

use Citagora\Common\Model;

class Contributor extends Model
{
    /**
     * @attribute
     * @var int
     */
    protected $id;

    /**
     * @attribute
     * @var string
     */
    protected $fullname;

    /**
     * @attribute
     * @var string
     */
    protected $givenname;

    /**
     * @attribute
     * @var string
     */
    protected $surname;

    /**
     * @attribute
     * @var string
     */
    protected $type;

    // --------------------------------------------------------------

    public function __tostring()
    {
        if ($this->surname && $this->givenname) {
            return $this->surname . ', ' . $this->givenname;
        }
        elseif ($this->fullname) {
            return $this->fullname;
        }
        elseif ($this->surname) {
            return $this->surname;
        }
        else {
            return '';
        }
    }
}


/* EOF: Contributor.php */