<?php

namespace Citagora\WebBundle\Model;

/** 
 * Class parses and defines search requests
 *
 * @TODO: Flesh this out based on the search API for the Java
 */
class SearchRequest
{
    /**
     * @var array
     */
    private $params;

    // --------------------------------------------------------------

    /**
     * Takes in a raw search string
     *
     * @param string $rawString
     */
    public function __construct($rawString)
    {

    }

    // --------------------------------------------------------------

    /**
     * Takes in a raw search string and sets up the object
     *
     * @param string $rawString
     */
    public function init($rawString)
    {

    }

    // --------------------------------------------------------------

    protected function parse($rawString)
    {
        //Step One -- Look for known regex patterns

        //DOI (that's it for now)
        //Borrowed from: http://stackoverflow.com/questions/27910/finding-a-doi-in-a-document-or-page
        $doiRegex = '/^(\b(10[.][0-9]{4,}(?:[.][0-9]+)*/(?:(?!["&\'<>])\S)+)\b|(10[.][0-9]{4,}(?:[.][0-9]+)*/(?:(?!["&\'<>])[[:graph:]])+))$/i';

        //

    }

}