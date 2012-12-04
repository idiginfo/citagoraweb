<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;
use InvalidArgumentException;

class SocialMetrics extends AbstractValueObject
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