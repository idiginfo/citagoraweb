<?php

namespace Citagora\Common\Entity\Document;

use Citagora\Common\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ODM\Document
 */
class Document extends Entity
{
    /**
     * @var int
     * @ODM\Id     
     */
    protected $id;

    /**
     * @var string
     * @ODM\String    
     */
    protected $title;

    /**
     * @var string
     * @ODM\String
     * @ODM\Index
     */
    protected $normalizedTitle;

    /**
     * @var string
     * @ODM\String       
     */
    protected $journal;

    /**
     * @var string
     * @ODM\String       
     */
    protected $publicationType;

    /**
     * @var int
     * @ODM\Int     
     */
    protected $year;

    /**
     * @var DateTime
     * @ODM\Date
     */
    protected $pubDate;

    /**
     * @var string
     * @ODM\String       
     */
    protected $pagination;

    /**
     * @var string
     * @ODM\String
     * @ODM\UniqueIndex
     */
    protected $doi;

    /**
     * @var string
     * @ODM\String       
     */
    protected $isbn;

    /**
     * @var string
     * @ODM\String
     */
    protected $issn;

    /**
     * @var string
     * @ODM\String
     * @ODM\UniqueIndex
     */
    protected $pmid;

    /**
     * @var string
     * @ODM\String  
     * @ODM\UniqueIndex
     */
    protected $url;

    /**
     * @var string
     * @ODM\String            
     */
    protected $abstract;

    /**
     * @var ArrayCollection
     * @ODM\EmbedMany(
     *    targetDocument="Rating"
     * )
     */
    protected $ratings;

    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(
     *     targetDocument="Contributor",
     *     cascade={"persist","refresh","merge"}
     * )
     */
    protected $contributors;

    /**
     * @var ArrayCollection
     * @ODM\EmbedMany(
     *    targetDocument="Citation"
     * )
     */
    protected $citations;

    /**
     * @var SocialMetrics
     * @ODM\EmbedOne(
     *    targetDocument="SocialMetrics"
     * )
     */
    protected $socialMetrics;

    /**
     * @var Meta
     * @ODM\EmbedOne(
     *    targetDocument="Meta"
     * )
     */
    protected $meta;

    /**
     * @var array
     * @ODM\Collection
     */
    protected $keywords;

    /**
     * @var array
     * @ODM\Hash
     */
    protected $unmappedFields;

    // --------------------------------------------------------------

    public function __construct()
    {
        //Initialize Everything
        $this->keywords       = array();
        $this->unmappedFields = array();
        $this->contributors   = new ArrayCollection();
        $this->citations      = new ArrayCollection();
        $this->ratings        = new ArrayCollection();
        $this->socialMetrics  = new SocialMetrics();
        $this->meta           = new Meta();
    }

    // --------------------------------------------------------------

    public function __set($item, $value)
    {
        switch ($item) {
            case 'contributors':
            case 'citations':
            case 'normalizedTitle':
            case 'ratings':
            case 'unmappedFields':
                throw new \Exception("Cannot modify {%item} property directly");
            break;
            case 'title':
                $this->setNormalizedTitle($value);
            break;
        }

        parent::__set($item, $value);
    }

    // --------------------------------------------------------------

    public function addRating(Rating $rating)
    {
        $this->ratings->add($rating);
    }

    // --------------------------------------------------------------

    public function addContributor(Contributor $contributor)
    {
        $this->contributors->add($contributor);
    }

    // --------------------------------------------------------------

    public function addCitation(Citation $citation)
    {
        $this->citations->add($citation);
    }

    // --------------------------------------------------------------

    public function addKeyword($keyword)
    {
        if ( ! in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }
    }

    // --------------------------------------------------------------

    /**
     * Add unmapped fields to record
     *
     * Merges existing unmapped fields from the source with new ones,
     * and overwrites any old unmapped fields that are sent as part of the
     * second parameter
     *
     * @param string $sourceSlug  The datasource slug
     * @param array  $fields      The fields
     */
    public function addUnmappedFields($sourceSlug, array $fields)
    {
        $unmappedFields = $this->unmappedFields;

        $existingUnmappedFields = isset($unmappedFields[$sourceSlug])
            ? $unmappedFields[$sourceSlug]
            : array();

        $unmappedFields[$sourceSlug] = array_filter(array_merge($existingUnmappedFields, $fields));
        $this->unmappedFields = $unmappedFields;
    }

    // --------------------------------------------------------------

    public function doiUrl()
    {
        if ($this->doi) {

            if (preg_match("/^http[s]?:\/\//i", $this->doi)) {
                return $this->doi;
            }
            else {
                return 'http://dx.doi.org/' . $this->doi;
            }

        }
        else {
            return null;
        }
    }    

    // --------------------------------------------------------------

    public function pmidUrl()
    {
        return ($this->pmid)
            ? 'http://www.ncbi.nlm.nih.gov/pubmed/' . $this->pmid
            : null;
    }    

    // --------------------------------------------------------------

    /**
     * Add a normalized title, too
     *
     * @param string $fullTitle
     */
    private function setNormalizedTitle($fullTitle)
    {
        //Strip out punctuation and special characters
        $title = strtolower(preg_replace("/[^a-zA-Z 0-9]+/", " ", $fullTitle));

        //Turn multiple spaces into single spaces
        $title = preg_replace('/(\s)(\s+)/', ' ', $title);

        //Set it
        $this->normalizedTitle = $title;
    }    
}

/* EOF: Document.php */