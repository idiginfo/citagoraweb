<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;
use InvalidArgumentException;

class Meta extends AbstractValueObject
{
    /**
     * @var array
     */
    protected $sources;

    /**
     * @var int  Generated by
     * Either a user ID or '0' for harvester
     */
    protected $generatedBy;

    /**
     * @var DateTime
     * Date record was added in system
     */
    protected $created;

    /**
     * @var DateTime
     * Date record last modified
     */
    protected $modified;

}

/* EOF: Meta.php */