<?php

namespace Citagora\Common\Harvester;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

use Citagora\Common\Tool\DocumentFactory;
use Citagora\Common\EntityCollection\DocumentCollection;
use Citagora\Common\DataSource\Type\Base as DataSource;
use Citagora\Common\Events;

/**
 * Document Harvester
 */
class Harvester
{
    /**
     * @var Citagora\Tool\DocumentFactory
     */
    private $documentFactory;

    /**
     * @param Citagora\EntityCollection\DocumentCollection
     */
    private $documentCollection;

    /**
     * @var Symfony\..\EventDispatcher
     */
    private $eventDispatcher;

    // --------------------------------------------------------------

    /** 
     * Constructor
     *
     * @param Citagora\Tool\DocumentFactory
     * @param Citagora\EntityCollection\DocumentCollection
     */
    public function __construct(DocumentFactory $df, DocumentCollection $dc)
    {
        $this->documentFactory    = $df;
        $this->documentCollection = $dc;
    }

    // --------------------------------------------------------------

    /**
     * Set optional event dispatcher to notify other objects of events
     *
     * This is used for logging or displaying output to a terminal..
     *
     * @param Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    } 

    // --------------------------------------------------------------

    /**
     * Do harvest operation
     *
     * @param  DataSourceList $dataSources  Data sources
     * @param  array          $options      Options
     * @param  int            $limit        Overall limit (0 for unlimited)
     * @return array          Array of reports from each harvester
     */
    public function harvestFromMany(array $dataSources, $options, $limit = 0)
    {
        //Overall count of records harvested
        $counts = array('total' => 0);

        //Iterate through each datasource we want to connect to
        foreach ($dataSources as $dataSource) {

            //Exceeded overall limit?  Get out.
            if ($limit && $count >= $limit) {
                break;
            }

            //Get the data source name
            $name = $dataSource->getSlug();

            //Harvest options for this datasource (runtime limit?)
            if (isset($options[$name]['params'])) {
                $params = $options[$name]['params'];
            }

            //Get datastore limit
            $dsLimit = (isset($options[$name]['limit'])) ? $options[$name]['limit'] : 0;

            //If overall limit... modify datastore limit accordingly
            if ($limit && ($limit - $count) < $dsLimit) {
                $dsLimit = $limit - $count;
            }

            //Do it and update counts
            $counts[$name]    = $this->harvestFromSingle($dataSource, $params, $dsLimit);
            $counts['total'] += $counts[$name];
        }

        //Return the number processed for each
        return $counts;
    }

    // --------------------------------------------------------------

    /**
     * Get records from a single data source
     *
     * @param  DataSource $dataSource
     * @param  array      $params     DataSource-specific parameters
     * @param  int        $limit
     * @return int        Count of records processed
     */
    public function harvestFromSingle(DataSource $dataSource, array $params, $limit = 0)
    {
        //Set count
        $count = 0;

        //Notify and set parameters
        $this->dispatch(Events::HARVESTER_SOURCE_CONNECT, $dataSource, array('limit' => $limit));
        $dataSource->setParameters($params);


        //Connect to datasource
        $result = $dataSource->connectToSource($params);
        $this->dispatch(Events::HARVESTER_SOURCE_CONNECT_RESULT, $result, $params);

        //If could not connect, fail out
        if ( ! $result) {
            continue;
        }

        //While getNextRecord...
        while ($sourceRec = $dataSource->getNextRecord($params)) {                

            if ($limit && $count >= $limit) {
                break;
            }

            //Map record
            $newrec   = $this->documentFactory->factory('Document');
            $document = $dataSource->mapRecord($sourceRec, $newrec, $this->documentFactory);

            //Save record (and flush it and detach it)
            $this->documentCollection->save($document, true, true);

            //Determine total document count for reporting...
            $totalDocumentCount = $this->documentCollection->getQueryBuilder()->getQuery()->execute()->count();

            //Dispatch event for additional tasks to be done upon harvest
            $this->dispatch(Events::HARVESTER_PROCESS_RECORD, $document, array('totalDocCount' => $totalDocumentCount));

            //Increment counter
            $count++;

            //Cleanup
            unset($newrec, $document, $sourceRec);
        }

        $this->dispatch(Events::HARVESTER_SOURCE_FINISHED, $count);
        return $count;
    }

    // --------------------------------------------------------------

    /**
     * Dispatch an event
     *
     * @param string $eventName  Refer to Symfony Event dispatcher naming rules
     * @param string $subject    The event subject
     * @param array  $arguments  Any additional data, if desired
     */
    protected function dispatch($eventName, $subject, array $arguments = array())
    {
        if ($this->eventDispatcher) {
            $event = new GenericEvent($subject, $arguments);
            $this->eventDispatcher->dispatch($eventName, $event);
        }
    }

}

/* EOF: Harvester.php */