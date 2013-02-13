<?php

namespace Citagora\Common\Model\Document;

use Citagora\Common\Model;

/**
 * Abstract Annotation Class
 */
abstract class Annotation extends Model
{
    /**
     * Get the type of annotation
     *
     * @return string
     */
    public function getType()
    {
        //Returns the basename of the called class
        return join('', array_slice(explode('\\', get_called_class()), -1));
    }
}

/* EOF: Annotation.php */