<?php

namespace Citagora\Amender;
use Citagora\Entity\Document\Document;

/**
 * Record Amender Abstract Class
 */
abstract class AmenderAbstract
{
    /**
     * Amend an existing document
     *
     * @return Document
     */
    abstract public function amend(Document $document);

    /**
     * Test the amender
     *
     * @return boolean
     */
    abstract public function test();
}

/* EOF: AmenderAbstract.php */