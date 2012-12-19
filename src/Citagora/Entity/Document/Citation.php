<?php

namespace Citagora\Entity\Document;
use Citagora\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Citation extends Entity
{
    /**
     * @var int
     * @ODM\Id
     */
    protected $id;

    /**
     * @var Document
     * @ODM\ReferenceOne(
     *     targetDocument="Document"
     * )
     */
    protected $document;
}

/* EOF: Citation.php */