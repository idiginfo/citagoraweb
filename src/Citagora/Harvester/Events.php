<?php

namespace Citagora\Harvester;

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
}

/* EOF: Events.php */