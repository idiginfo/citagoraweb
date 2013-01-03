<?php

namespace Citagora\Entity\Document;
use Citagora\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="DocumentContributor")
 */
class Contributor extends Entity
{
    /**
     * @var int
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     * @ODM\String
     */
    protected $fullname;

    /**
     * @var string
     * @ODM\String     
     */
    protected $givenname;

    /**
     * @var string
     * @ODM\String     
     */
    protected $surname;

    /**
     * @var string
     * @ODM\String     
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