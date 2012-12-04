<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;
use InvalidArgumentException;

class SocialMetrics
{
    /**
     * @var float
     */
    private $numTweets;

    /**
     * @var float
     */
    private $numMendeley;
 
    /**
     * @var float
     */
    private $numCiteulike;

    /**
     * @var float
     */
    private $numConnotea;
}

/* EOF: SocialMetrics.php */