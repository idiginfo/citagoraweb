<?php

namespace Citagora\Harvester;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;

use TaskTracker\OutputHandler\SymfonyConsole as TrackerConsoleHandler;
use TaskTracker\Tracker;

/**
 * Event listener for Harvester for reporting progress to command-line
 */
class CliTracker
{
    /**
     * @var Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @var TaskTracker\Tracker
     */
    private $tracker;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Symfony\Component\EventDispatcher\EventDispatcher
     * @param Symfony\Component\Console\Output\OutputInterface
     */
    public function __construct(EventDispatcher $dispatcher, OutputInterface $output)
    {
        $dispatcher->addListener(Events::NEXT_SOURCE,      array($this, 'nextSourceNotify'));
        $dispatcher->addListener(Events::CONNECT_RESULT,   array($this, 'connectResult'));
        $dispatcher->addListener(Events::RECORD_PROCESSED, array($this, 'recordProcessed'));
        $dispatcher->addListener(Events::FINISHED_SOURCE,  array($this, 'finishedSource'));

        $this->output = $output;
    }

    // --------------------------------------------------------------

    /**
     * Notify that next data source has been popped off the queue for processing
     *
     * Also sets up the progress bar
     *
     * @param Event $event  
     */
    public function nextSourceNotify(Event $event)
    {
        $source = $event->getSubject();
        $args   = $event->getArguments();
        $limit  = $args['limit'];

        //Print a notice
        $this->output->writeln(sprintf(
            "Attempting to harvest %s records from %s...",
            $limit ? number_format($limit, 0) . ' records' : 'as many records as possible',
            $source->getName()
        ));

        //Setup a tracker
        $this->tracker = new Tracker(
            new TrackerConsoleHandler($this->output),
            $limit ?: Tracker::UNKNOWN
        );
    }

    // --------------------------------------------------------------

    /**
     * Notify if connecting to a data source succeeded or failed
     *
     * @param Event $event  
     */
    public function connectResult(Event $event)
    {
        $connectResult =$event->getSubject();

        if ($connectResult) {
            $this->output->writeln("Connected Succesfully");
        }
        else {
            $this->output->writeln("Connect failed.  Skipping source...");
        }
        
    }

    // --------------------------------------------------------------

    /**
     * Notify when record has been processed
     *
     * Updates the task tracker progress bar
     *
     * @param Event $event  
     */
    public function recordProcessed(Event $event)
    {
        $args = $event->getArguments();
        $totalCount = $args['totalDocCount'];

        $this->tracker->tick(sprintf(
            'Harvesting (%s now in database)',
            number_format($totalCount, 0)
        ));
    }

    // --------------------------------------------------------------

    /**
     * Notify when source finished
     *
     * @param Event $event  
     */
    public function finishedSource(Event $event)
    {
        if ($this->tracker) {
            $this->tracker->finish();
        }

        $this->tracker = null;
    }
}
/* EOF: HarvesterCliTracker.php */