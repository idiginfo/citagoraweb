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
     * @ODM\Int    
     */
    protected $numTweets;

    /**
     * @var int
     * @ODM\Int     
     */
    protected $numMendeley;
 
    /**
     * @var int
     * @ODM\Int     
     */
    protected $numCiteulike;

    /**
     * @var int
     * @ODM\Int     
     */
    protected $numConnotea;

    /**
     * @var int
     * @ODM\Int     
     */
    protected $numDisqus;
}

/* EOF: SocialMetrics.php */