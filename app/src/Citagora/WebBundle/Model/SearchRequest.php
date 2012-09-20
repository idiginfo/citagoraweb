<?php

namespace Citagora\WebBundle\Model;

/**
 * Class parses and defines search requests
 *
 * @TODO: Flesh this out based on the search API for the Java
 * @TODO: Deal with non-equals (=) operators better (!=, >=, <=)
 */
class SearchRequest
{
    /**
     * @var array
     * Keys are facet names / values are facet values
     */
    private $facets = array();

    /**
     * @var array
     * Incremental array
     */
    private $keywords = array();

    // --------------------------------------------------------------

    /**
     * Takes in a raw search string
     *
     * @param string $rawString
     */
    public function __construct($rawString)
    {
        $this->parse($rawString);
    }

    // --------------------------------------------------------------

    public function getKeywords()
    {
        return $this->keywords;
    }

    // --------------------------------------------------------------

    public function getFacets()
    {
        return $this->facets;
    }

    // --------------------------------------------------------------

    public function __toString()
    {
        return $this->getQuery();
    }


    // --------------------------------------------------------------

    /**
     * Represent this as a GET query
     *
     * @return string
     */
    public function getQuery()
    {
        if (count($this->keywords) > 0) {
            $keywords = implode(',', $this->keywords);
            $query = array_merge($this->facets, array('keywords' => $keywords));
        }
        else {
            $query = $facets;
        }

        return http_build_query($query);
    }

    // --------------------------------------------------------------

    /**
     * Parse the search query into facets
     *
     * @param string $rawString
     */
    protected function parse($rawString)
    {
        //Empty Arrays
        $facets = array();
        $keywords = array();

        //Step One -- Look for known regex patterns like DOI
        $regexMatch = $this->regexMatch($rawString);
        $rawString = $regexMatch ?: $rawString;

        //Step Two -- Break it up by its component parts
        if (strpos($rawString, '[') !== false) {

            //Get the keywords using preg_split
            $keywords = preg_split("/\[.+?=.+?\]/i", $rawString);
            $keywords = array_filter(array_map('trim', $keywords));

            //Get the patterns using preg_match_all
            preg_match_all("/\[(.+?=.+?)\]/i", $rawString, $matches);
            if (isset($matches[1])) {

                array_map(function($v) use (&$facets) {

                    list($facet,$value) = array_map('trim', explode('=', $v, 2));
                    $facets[$facet] = $value;

                }, $matches[1]);
            }
        }
        else {
            $keywords = array_filter(array_map('trim', explode(" ", $rawString)));
        }

        $this->facets = $facets;
        $this->keywords = $keywords;
    }

    // --------------------------------------------------------------

    /**
     * Convert plaintext to a faceted search based on regex matches
     *
     * For example, if provided with a string that is clearly a DOI,
     * format it in the expected raw string query format for this class
     * to process.
     *
     * @param string $rawString
     * @return string|boolean
     */
    protected function regexMatch($rawString)
    {
        //Facet name is key; Matching regex is value
        //Returns first match, so order matters!
        $patterns = array();

        //DOI (that's it for now)
        //Borrowed from: http://stackoverflow.com/questions/27910/finding-a-doi-in-a-document-or-page
        $patterns['doi'] = "/^DOIREGEXHERE$/i";

        //Return first match
        foreach($patterns as $facet => $regex) {
            if (preg_match($regex, $rawString, $matches)) {
                return sprintf("[%s=%s]", $facet, $matches[0]);
            }
        }

        //If made it here..
        return false;
    }

}