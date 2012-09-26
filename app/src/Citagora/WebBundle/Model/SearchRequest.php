<?php

namespace Citagora\WebBundle\Model;
use Sequin\Query as SequinQuery;

/**
 * Service Class that parses and defines search requests 
 * and builds a lucene-compatible search query string
 */
class SearchRequest
{
    /**
     * @var array  Of strings
     */
    private $knownFacets;

    // --------------------------------------------------------------

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->knownFacets = array();
    }

    // --------------------------------------------------------------

    /**
     * Get known facets
     *
     * @return array
     */
    public function getKnownFacets()
    {
        return $this->knownFacets;
    }

    // --------------------------------------------------------------

    /**
     * Set known facets
     *
     * This is useful for helping determine if a string sent
     * is a valid, well-formed query string
     *
     * @param array $facets
     */
    public function setKnownFacets(Array $facets)
    {
        $this->knownFacets = $facets;
    }

    // --------------------------------------------------------------

    /**
     * Parse into possible Lucene queries, ranked by
     * priority order
     *
     * @param string $str  The raw string
     * @return array       Array of Lucene strings, in order
     */
    public function parse($str)
    {
        $outQueries = array();

        //1. Look for known regex patterns
        if ($outStr = $this->regexMatch($str)) {
            $outQueries[] = $outStr;
        }

        //2. Check if valid Lucene String already
        elseif ($outStr = $this->luceneQueryMatch($str)) {
            $outQueries[] = $outStr;
        }

        //3. If none of the above, return two queries:
        //title match and tokenized
        else {
            $outQueries[] = sprintf("title:\"%s\"", addslashes($str));
            $outQueries[] = implode(' ', preg_split("/\b/", $str));
        }

        return $outQueries;
    }

    // --------------------------------------------------------------

    /**
     * Check if the string is a lucene query string
     *
     * @todo   Fill this in!
     * @todo   Make this its own class?
     * @param  string
     * @return string|boolean  false, if it is not a lucene-compatible string
     */
    protected function luceneQueryMatch($str)
    {
        //Must satisfy a number of regex statements that must pass
        $mustSatisfy = array();

        //Valid characters
        $mustSatify[] = "/^[a-zA-Z\p{L}0-9_+\-:.()\"*?&|!{}\[\]\^~\\@#\/$%'= ]+$/";
        
        //Wildcard (*) must be preceded by at least one alphabet or number

        //Queries containing the special characters && must be in the form: term1 && term2

        //The caret (^) character must be preceded by alphanumeric characters and followed by numbers

        //The tilde (~) character must be preceded by alphanumeric characters and followed by numbers

        //Queries containing the special character ! must be in the form: term1 ! term2.

        //The question mark (?) character must be preceded by at least one alphabet or number.

        //Parentheses must be closed.

        //Parentheses must contain at least one character.

        //'+' and '-' modifiers must be followed by at least one alphabet or number.

        //Queries containing AND/OR/NOT must be in the form: term1 AND|OR|NOT|AND NOT term2

        //Queries containing AND/OR/NOT must be in the form: term1 AND|OR|NOT|AND NOT term2

        //Quote (\") marks must be closed

        //Quote marks must contain at least one character.

        //Field declarations (:) must be preceded by at least one alphabet or number and followed by at least one alphabet or number

        foreach ($mustSatisfy as $regex) {

            if ( ! preg_match($regex)) {
                return false;
            }
        }

        if (count($this->getKnownFacets()) > 0) {

            //@TODO: Check to see if all field defintions "\b(A-Za-z0-9-_):" match
            //known field definitions and return false if not
        }

        //If made it here, we can be reasonably sure
        return $str;
    }

    // --------------------------------------------------------------

    /**
     * Get known regex patterns
     *
     * Define array as: ['facetName'] => '/(REGEXPATTERN)/'
     *
     * The regexpattern needs to have one matching set "parentheses".
     * * This will work: "/somethingto(match)/i" 
     * * This will not:  "/somethingtomatch/i"
     * * This will match the first paren: "/(some)thingto(match)/i" -- 'some' will be used
     *
     * @return array  Array of patterns to match, in the order they should be checked
     */
    protected function getKnownRegexPatterns()
    {
        return array(
            //See: http://stackoverflow.com/questions/27910/finding-a-doi-in-a-document-or-page
            'doi' => "/^(DOIREGEXHERE)$/i"
        );
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
        //Foreach regex pattern
        foreach($this->getKnownRegexPatterns() as $facet => $regex) {
            if (preg_match($regex, $rawString, $matches)) {
                return sprintf("%s:\"%s\"", $facet, $matches[1]);
            }
        }

        //If made it here..
        return false;
    }

}