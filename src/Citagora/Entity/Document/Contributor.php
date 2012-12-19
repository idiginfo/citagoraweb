<?php

namespace Citagora\Entity\Document;
use Citagora\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
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
        $gn = ($this->givenname)
            ? ', ' . $this->givenname
            : null;

        return $this->surname . $gn;
    }
}


/* EOF: Contributor.php */