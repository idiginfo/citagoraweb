<?php

namespace Citagora\TestFixture;

use Citagora\Common\Model\Model;

class DummyModel extends Model
{
    /**
     * @var string
     * @Attribute
     */
    protected $name;

    /**
     * @var int
     * @Attribute
     */
    protected $age;

    /**
     * @attribute    Lowercase annotation test
     * @var string
     */
    protected $gender;

    /**
     * This is not an @Attribute, and should not
     * be read as one
     *
     * @var array
     */
    protected $someInternalThing;

    /**
     * @var int
     */
    private $anotherInternalThing;

    /**
     * @var string
     * @Attribute
     */
    public $favoriteFood;

    /**
     * @var string
     */
    public $favoriteColor;

    // --------------------------------------------------------------

    public function setSomeInternalThing($val)
    {
        $this->someInternalThing = $val;
    }
}

/* EOF: DummyModel.php */