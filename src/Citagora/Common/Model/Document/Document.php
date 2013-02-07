<?php

namespace Citagora\Common\Entity\Document;

use Doctrine\Common\Collections\ArrayCollection;
use InavlidArgumentException;


class Document extends Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     * @ODM\Index
     */
    protected $normalizedTitle;

    /**
     * @var string
     */
    protected $journal;

    /**
     * @var string
     */
    protected $publicationType;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var DateTime
     */
    protected $pubDate;

    /**
     * @var string
     */
    protected $pagination;

    /**
     * @var string
     * @ODM\UniqueIndex
     */
    protected $doi;

    /**
     * @var string
     */
    protected $isbn;

    /**
     * @var string
     */
    protected $issn;

    /**
     * @var string
     * @ODM\UniqueIndex
     */
    protected $pmid;

    /**
     * @var string
     * @ODM\UniqueIndex
     */
    protected $url;

    /**
     * @var string
     */
    protected $abstract;

    /**
     * @var ArrayCollection
     *    targetDocument="Review",
     *    mappedBy="document"
     * )
     */
    protected $reviews;

    /**
     * @var ArrayCollection
     *     targetDocument="Contributor",
     *     cascade={"persist","refresh","merge"}
     * )
     */
    protected $contributors;

    /**
     * @var ArrayCollection
     *    targetDocument="Citation"
     * )
     */
    protected $citations;

    /**
     * @var SocialMetrics
     *    targetDocument="SocialMetrics"
     * )
     */
    protected $socialMetrics;

    /**
     * @var Meta
     *    targetDocument="Meta"
     * )
     */
    protected $meta;

    /**
     * @var array
     */
    protected $keywords;

    /**
     * @var array
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
        $this->reviews        = new ArrayCollection();
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
            case 'reviews':
            case 'unmappedFields':
                throw new \Exception("Cannot modify {$item} property directly");
            break;
            case 'title':
                $this->setNormalizedTitle($value);
            break;
        }

        parent::__set($item, $value);
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

    /**
     * Add keyword
     *
     * @param string $keyword
     */
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

    /**
     * Get the DOI URL
     *
     * @return string
     */
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

    /**
     * Get the Pubmed ID Url
     *
     * @return string
     */
    public function pmidUrl()
    {
        return ($this->pmid)
            ? 'http://www.ncbi.nlm.nih.gov/pubmed/' . $this->pmid
            : null;
    }

    // --------------------------------------------------------------

    /**
     * Aggregate ratings for a category
     *
     * @return array   A simple data structure with (rating_slug => [name, value, count])
     */
    public function aggregateRatings()
    {
        $values = array();
        foreach(Review::getRatingCategories() as $slug => $hname) {
            $values[$slug] = (object) array(
                'name'  => $hname,
                'value' => 0,
                'count' => 0,
                'score' => 0
            );
        }

        foreach ($this->reviews as $review) {

            foreach($review->ratings as $slug => $val) {
                $values[$slug]->count++;
                $values[$slug]->score += $val;
                $values[$slug]->value = $values[$slug]->score / $values[$slug]->count;
            }

        }

        return $values;
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