<?php

namespace Citagora\Entity\Document;
use Citagora\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class SocialMetrics extends Entity
{
    /**
     * @var int
     */
    protected $numTweets;

    /**
     * @var int
     */
    protected $numMendeley;
 
    /**
     * @var int
     */
    protected $numCiteulike;

    /**
     * @var int
     */
    protected $numConnotea;

    /**
     * @var int
     */
    protected $numDisqus;
}

/* EOF: SocialMetrics.php */