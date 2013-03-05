<?php

namespace Citagora\Common\Model;
use Citagora\CitagoraTest, Mockery;
use Citagora\TestFixture\DummyModel;

/**
 * Test the Model Abstract Class
 */
class ModelTest extends CitagoraTest
{
    /**
     * Test Instantiation
     */
    public function testInstantiateSucceeds()
    {
        $this->assertInstanceOf('Citagora\Common\Model\Model', $this->getObject());
    }

    // --------------------------------------------------------------

    /**
     * The __get() magic method should work for properties with @Attribute annotation
     */
    public function testGetAttributeNamesReturnsCorrectValues()
    {
        $obj = $this->getObject();

        $expectedAttributes = array(
            'name', 'age', 'gender', 'favoriteFood'
        );

        $this->assertEquals($expectedAttributes, $obj::getAttributeNames());
    }

    // --------------------------------------------------------------

    /**
     * The __set() magic method should work for properties with @Attribute annotation
     */
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

    /**
     * The __set() magic method should fail for protected properties not tagged as attributes
     */
    public function testSetMagicMethodFailsForNonAttributeProperty()
    {
        $this->setExpectedException('\Exception');

        $obj = $this->getObject();
        $obj->someInternalThing = 'blah';
    }

    // --------------------------------------------------------------

    /**
     * THe __get() magic method should fail for protected properties not tagged as attributes
     */
    public function testGetMagicMethodFailsForNonAttributeProperty()
    {
        $this->setExpectedException('\Exception');

        $obj = $this->getObject();
        $obj->someInternalThing;
    }

    // --------------------------------------------------------------

    /**
     * The __isset() should work for attribute properties the same as it would for public properties
     */
    public function testIssetReturnsTrueForSetAttribute()
    {
        $obj = $this->getObject();

        $obj->age = 32;
        $this->assertTrue(isset($obj->age));
    }

    // --------------------------------------------------------------

    /**
     * The __isset() should work for attribute properties the same as it would for public properties
     */
    public function testIssetReturnsFalseForNonSetAttribute()
    {
        $obj = $this->getObject();
        $this->assertFalse(isset($obj->age));
    }

    // --------------------------------------------------------------

    /**
     * The __isset() should throw an exception for non-attribute protected properties
     */
    public function testIssetThrowsExceptionForNonAttributeProperty()
    {
        $this->setExpectedException('\Exception');

        $obj = $this->getObject();
        $obj->setSomeInternalThing('blah');

        isset($obj->someInternalThing);
    }


    // --------------------------------------------------------------

    /**
     * toArray() should return the attributes as an array
     */
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

    /**
     * __toString() should return a JSON encoded array of attributes
     */
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