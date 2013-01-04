<?php

namespace Citagora\Command;

use Silex\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use RuntimeException, Console_table;
use Citagora\Harvester\CliTracker;

use TaskTracker\OutputHandler\SymfonyConsole as TrackerConsoleHandler;
use TaskTracker\Tracker;

/**
 * Document Harvest Command
 */
class DocumentHarvest extends CommandAbstract
{
    /**
     * @var Harvester
     */
    private $harvester;

    /**
     * @var Pimple  DiC Container with Data Sources in it
     */
    private $dataSources;

    /**
     * @var EntityManager  Used for clobbering, if desired
     */
    private $em;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    // --------------------------------------------------------------

    protected function configure()
    {
        $this->setName('docs:harvest');
        $this->setDescription('Harvest Records from a configured sources');
        $this->AddArgument('source', InputArgument::REQUIRED, 'Harvest records from specified source');
        $this->addOption('clobber',  'c', InputOption::VALUE_NONE,     'If specified, this command will clobber all records');
        $this->addOption('limit',    'l', InputOption::VALUE_REQUIRED, 'Optionally specify a limit of records to import per source');
        $this->addOption('options',  'o', InputOption::VALUE_REQUIRED, 'If using -s, specify options for that source with -o');
    }

    // --------------------------------------------------------------

    protected function init(Application $app)
    {
        $this->harvester   = $app['harvester'];
        $this->dispatcher  = $app['dispatcher'];
        $this->dataSources = $app['data_sources'];
        $this->em          = $app['em'];
    }

    // --------------------------------------------------------------

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        //Source
        $source = $input->getArgument('source');
        if ( ! isset($this->dataSources[$source])) {
            throw new RuntimeException("Source '{$source}' does not exist.  Use docs:sources to see available sources");
        }
        $dataSource = $this->dataSources[$source];

        //If clobber
        if ($input->getOption('clobber')) {
            $result = $this->clobber($output);

            if ( ! $result) {
                $output->writeln("Action cancelled");
                return;
            }
        }

        //Limit and options
        $limit  = $input->getOption('limit');
        $params = $this->readParams($input);

        //Event Listnener
        $listener = new CliTracker($this->dispatcher, $output);

        //Do it...
        $count = $this->harvester->harvestFromSingle($dataSource, $params, $limit);

        //Report
        $output->writeln(sprintf(
            "Imported %s records from %s",
            number_format($count, 0),
            $dataSource->getName()
        ));
    }

    // --------------------------------------------------------------

    /**
     * Get parameters (called options in the CLI)
     */
    private function readParams(InputInterface $input)
    {
        $params = array();     
           
        //Get params
        if ($input->getOption('options')) {
            $params = explode(',' , $input->getOption('params'));
            foreach($params as $k => $param) {
                list($pname, $value) = explode('=', $param);
                $params[$pname] = $value;
                unset($params[$k]);
            }
        }

        return $params;
    }

    // --------------------------------------------------------------

    /**
     * Clobber database
     * 
     * @return boolean  TRUE if action completed, FALSE if action cancelled
     */
    private function clobber(OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        if ( ! $dialog->askConfirmation($output,
                '<error>WARNING: This will DESTROY all data.</error> Are you sure you want to do this? [y/n]: ', false
            )) {
            return false;
        }

        //Clobber the Document and DocumentContributor collections
        $toClobber = array('Document\Document', 'Document\Contributor');
        
        foreach($toClobber as $clobber) {
            $repo    = $this->em->getCollection($clobber)->getRepository()->getClassName();
            $collObj = $this->em->getCollection($clobber)->getRepository()
                        ->getDocumentManager()->getDocumentCollection($repo);

            //Do it
            $result = $collObj->drop();
            if ($result['ok']) {
                $output->writeln("Dropped {$clobber} collection ({$repo})");
            }
            else {
                $output->writeln("No {$clobber} collection to drop.  Moving on...");  
            }
        }

        return true;
    } 
}

/* EOF: RecordsHarvest.php */