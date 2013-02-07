<?php

namespace Citagora\Common\Entity\Document;

class Contributor extends Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $fullname;

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