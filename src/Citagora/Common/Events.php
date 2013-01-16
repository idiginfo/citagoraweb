<?php

namespace Citagora\Common;

/**
 * Events Definition for Citagora Common Libraries
 *
 * Refer to http://symfony.com/doc/2.0/components/event_dispatcher/introduction.html
 * for how this works
 */
final class Events
{
    /**
     * Thrown each time the next source is popped off the queue
     * in the harvester
     *
     * @var string
     */
    const HARVESTER_SOURCE_CONNECT = 'harvester.next_source';

    /**
     * Thrown each time a connection is attempted to a data source
     *
     * @var string
     */
    const HARVESTER_SOURCE_CONNECT_RESULT = 'harvester.connect_result';

    /**
     * Thrown each time a record is processed
     *
     * @var string
     */
    const HARVESTER_PROCESS_RECORD = 'harvester.record_processed';


    /**
     * Thrown each time a record is processed
     *
     * @var string
     */
    const HARVESTER_SOURCE_FINISHED = 'harvester.finished_source';    

    /**
     * Thrown when a document is saved in the system
     *
     * @var string
     */
    const ENTITY_DOCUMENT_SAVE = 'entities.document.save';
}
