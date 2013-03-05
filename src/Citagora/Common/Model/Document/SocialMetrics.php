<?php

namespace Citagora\Common\Model\Document;

use Citagora\Common\Model;

class SocialMetrics extends Model
{
    /**
     * @var int
     * @attribute
     */
    protected $numTweets;

    /**
     * @var int
     * @attribute
     */
    protected $numMendeley;

    /**
     * @var int
     * @attribute
     */
    protected $numCiteulike;

    /**
     * @var int
     * @attribute
     */
    protected $numConnotea;

    /**
     * @var int
     * @attribute
     */
    protected $numDisqus;
}

/* EOF: SocialMetrics.php */