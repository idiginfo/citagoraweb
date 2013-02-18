<?php

namespace Citagora;

use PHPUnit_Framework_TestCase;

abstract class CitagoraTest extends PHPUnit_Framework_TestCase
{
    /* pass for now */
    abstract function testInstantiateSucceeds();
}

/* EOF: CitagoraTest.php */