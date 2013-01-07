<?php

namespace Citagora\Harvester;

/**
 * Events Definition for Citagora Harvester
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
    const NEXT_SOURCE = 'harvester.next_source';

    /**
     * Thrown each time a connection is attempted to a data source
     *
     * @var string
     */
    const CONNECT_RESULT = 'harvester.connect_result';

    /**
     * Thrown each time a record is processed
     *
     * @var string
     */
    const RECORD_PROCESSED = 'harvester.record_processed';


    /**
     * Thrown each time a record is processed
     *
     * @var string
     */
    const FINISHED_SOURCE = 'harvester.finished_source';    
}

/* EOF: Events.php */