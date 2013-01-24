<?php

namespace Citagora\Common\Solr;

use Citagora\Common\Entity\Document;

use Citagora\Common\Events as CitagoraEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Solarium\Client as SolariumClient;
/**
 * Class for indexing documents in SOLR
 */
class DocumentIndexer implements EventSubscriberInterface;
{
    private $solr;

    // --------------------------------------------------------------

    public function __construct(SolariumClient $solr)
    {
        $this->solr = $solr;
    }

    // --------------------------------------------------------------

    public function indexDocument(Document $document)
    {
        //Map the document fields to an UPDATE query for SOLR

        //Indicate that the document has been mapped to SOLR
    }

    // --------------------------------------------------------------

    /**
     * Event listener
     */
    public function onDocumentSave(GenericEvent $event)
    {
        $document = $event->getSubject();
        return $this->indexDocument($document);
    }

    // --------------------------------------------------------------

    /**
     * Event subscriber status
     */
    public static function getSubscribedEvents()
    {
        return array(
            CitagoraEvents::ENTITY_DOCUMENT_SAVE => 'onDocumentSave'
        );
    }
}

/* EOF: DocumentIndexer.php */