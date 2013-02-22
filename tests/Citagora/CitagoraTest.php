<?php

namespace Citagora;

use PHPUnit_Framework_TestCase, Mockery;

abstract class CitagoraTest extends PHPUnit_Framework_TestCase
{
    /* pass for now */
    abstract function testInstantiateSucceeds();

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }
}

/* EOF: CitagoraTest.php */