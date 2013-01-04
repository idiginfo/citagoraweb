<?php

namespace Citagora\Harvester;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class CliTracker
{
    /**
     * @var Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    // --------------------------------------------------------------

    /**
     * Constructor
     * @param Symfony\Component\EventDispatcher\EventDispatcher
     * @param Symfony\Component\Console\Output\OutputInterface
     */
    public function __construct(EventDispatcher $dispatcher, OutputInterface $output)
    {
        $dispatcher->addListener('harvester.next_source',      array($this, 'nextSourceNotify'));
        $dispatcher->addListener('harvester.connect_result',   array($this, 'connectResult'));
        $dispatcher->addListener('harvester.record_processed', array($this, 'recordProcessed'));

        $this->output = $output;
    }

    // --------------------------------------------------------------

    public function nextSourceNotify(Event $event)
    {

    }

    // --------------------------------------------------------------

    public function connectResult(Event $event)
    {

    }

    // --------------------------------------------------------------

    public function recordProcessed(Event $event)
    {

    }

}
/* EOF: HarvesterCliTracker.php */