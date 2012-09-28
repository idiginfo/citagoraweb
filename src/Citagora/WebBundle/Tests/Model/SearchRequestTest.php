<?php

namespace Citagora\WebBundle\Tests\Model;

use Citagora\WebBundle\Model\SearchRequest;

class SearchRequestTest extends \PHPUnit_Framework_TestCase
{
    // --------------------------------------------------------------

    public function testInstantiateAsObjectSucceeds()
    {
        $obj = new SearchRequest('abc');
        $this->assertInstanceOf('Citagora\WebBundle\Model\SearchRequest', $obj);
    }

    // --------------------------------------------------------------

    public function testParseReturnsKeywordArrayForNonFactedSearchString()
    {
        $obj = new SearchRequest('abc def');
        $this->assertEquals(array('abc', 'def'), $obj->getKeywords());
        $this->assertEmpty($obj->getFacets());
    }

    // --------------------------------------------------------------

    public function testGetQueryReturnsSimpleQueryForNonFacetedSearchString()
    {
        $obj = new SearchRequest('abc def');
        $this->assertEquals('keywords=abc%2Cdef', $obj->getQuery());
    }

    // --------------------------------------------------------------

    public function testParseReturnsFacetsForFactedSearchString()
    {
        $obj = new SearchRequest('[author=doe, john] abc [year>=1990] def [issn!=123456789.0]');

        $toMatch = array(
            'author' => 'doe, john',
            'year>'  => '1990',
            'issn!'  => '123456789.0'
        );

        $this->assertEquals($toMatch, $obj->getFacets());
    }

    // --------------------------------------------------------------

    public function testGetQueryReturnsComplexQueryForFactedSearchString()
    {
        $obj = new SearchRequest('[author=doe, john] abc [year>=1990] def [issn!=123456789.0]');
         
        $toMatch = "author=doe%2C+john&year%3E=1990&issn%21=123456789.0&keywords=abc%2Cdef";
        $this->assertEquals($toMatch, $obj->getQuery());       
    }
}