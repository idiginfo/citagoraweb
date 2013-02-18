<?php

namespace Citagora\Common\Model;
use Citagora\CitagoraTest, Mockery;
use TestFixture\DummyModel;

/**
 * Test the Model Abstract Class
 */
class ModelTest extends CitagoraTest
{
    public function testInstantiateSucceeds()
    {
        $this->assertInstanceOf('Citagora\Common\Model\Model', $this->getObject());
    }

    // --------------------------------------------------------------

    public function testGetAttributeNamesReturnsCorrectValues()
    {
        $obj = $this->getObject();

        $expectedAttributes = array(
            'name', 'age', 'gender', 'favoriteFood'
        );

        $this->assertEquals($expectedAttributes, $obj::getAttributeNames());
    }

    // --------------------------------------------------------------

    public function testGetAndSetMagicMethodsWorksForAttribute()
    {
        $obj = $this->getObject();

        //Test basic scalar
        $obj->name = 'Bob';
        $obj->gender = 'male';

        //Test array access
        $obj->favoriteFood = array('a', 'b', 'c');
        $obj->favoriteFood[] = 'd';

        $this->assertEquals('Bob', $obj->name);
        $this->assertEquals('male', $obj->gender);
        $this->assertContains('d', $obj->favoriteFood);
    }

    // --------------------------------------------------------------

    public function testSetMagicMethodFailsForNonAttributeProperty()
    {
        $this->setExpectedException('\Exception');

        $obj = $this->getObject();
        $obj->someInternalThing = 'blah';
    }

    // --------------------------------------------------------------

    public function testGetMagicMethodFailsForNonAttributeProperty()
    {
        $this->setExpectedException('\Exception');

        $obj = $this->getObject();
        $obj->someInternalThing;
    }

    // --------------------------------------------------------------

    public function testIssetReturnsTrueForSetAttribute()
    {
        $obj = $this->getObject();

        $obj->age = 32;
        $this->assertTrue(isset($obj->age));
    }

    // --------------------------------------------------------------

    public function testIssetReturnsFalseForNonSetAttribute()
    {
        $obj = $this->getObject();
        $this->assertFalse(isset($obj->age));
    }

    // --------------------------------------------------------------

    public function testIssetThrowsExceptionForNonAttributeProperty()
    {
        $this->setExpectedException('\Exception');

        $obj = $this->getObject();
        $obj->setSomeInternalThing('blah');

        isset($obj->someInternalThing);
    }


    // --------------------------------------------------------------

    public function testToArrayReturnsAttributesAsArray()
    {
        $obj = $this->getObject();
        $obj->name         = 'Bob';
        $obj->age          = 32;
        $obj->favoriteFood = 'Spagetti';

        $arr = $obj->toArray();

        $this->assertEquals(4, count($arr));
        $this->assertEquals('Bob', $arr['name']);
    }

    // --------------------------------------------------------------

    public function testToStringEncodesProtectedPropertiesToJson()
    {
        $obj = $this->getObject();
        $obj->name         = 'Bob';
        $obj->age          = 32;
        $obj->favoriteFood = 'Spagetti';

        $expected = '{"name":"Bob","age":32,"gender":null,"favoriteFood":"Spagetti"}';
        $this->assertEquals($expected, (string) $obj);
    }

    // --------------------------------------------------------------

    /**
     * Returns a fixture to test the object
     *
     * @return Model
     */
    private function getObject()
    {
        return new DummyModel();
    }
}

/* EOF: ModelTest.php */