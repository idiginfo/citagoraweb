<?php

namespace Citagora\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Silex\Application;
use Citagora\EntityCollection\DocumentCollection;
use Citagora\Harvester\HarvesterAbstract as Harvester;
use RuntimeException;

/**
 * Document Harvest Command
 */
class DocumentHarvest extends CommandAbstract
{
    /**
     * @var RecordCollection
     */
    private $documentCollection;

    /**
     * @var array  Array of Harvester objects
     */
    private $harvesters;

    // --------------------------------------------------------------

    public function __construct(DocumentCollection $documentCollection, array $harvesters)
    {
        parent::__construct();
        $this->documentCollection = $documentCollection;
        $this->harvesters = $harvesters;
    }

    // --------------------------------------------------------------

    protected function configure()
    {
        $this->setName('docs:harvest');
        $this->setDescription('Harvest Records from a configured sources');
        $this->addOption('clobber', 'c', InputOption::VALUE_NONE,     'If specified, this command will clobber all records');
        $this->addOption('limit',   'l', InputOption::VALUE_REQUIRED, 'Optionally specify a limit of records to import');
        $this->addOption('auto',    'a', InputOption::VALUE_NONE,     'Reads from config/harvest.yml to harvest from multiple sources');
        $this->addOption('source',  's', InputOption::VALUE_REQUIRED, 'Harvest records from specified source (overrides -a)');
        $this->addOption('options', 'o', InputOption::VALUE_REQUIRED, 'If using -s, specify options for that source with -o');
    }

    // --------------------------------------------------------------

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Get a list of sources to get records from
        $sources = $this->compileSources($input);

        //If clobber
        if ($input->getOption('clobber')) {
            $result = $this->clobber($output);

            if ( ! $result) {
                $output->writeln("Action cancelled");
                return;
            }
        }

        //Limit?
        $limit = $input->getOption('limit');
        
        //Get options
        if ($input->getOption('options')) {
            $options = explode(',' , $input->getOption('options'));
            foreach($options as $k => $option) {
                list($optname, $value) = explode('=', $option);
                $options[$optname] = $value;
                unset($options[$k]);
            }
        }
        else {
            $options = array();
        }

        //Run the harvester with options
        foreach($sources as $harvester) {

            //Message
            $output->writeln(sprintf(
                "Importing up to %s records from %s",
                ($limit) ? number_format($limit, 0) : "as many as possible",
                $harvester->getDescription()
            ));

            $this->importFromSource($harvester, $options, $limit);
        }
    }

    // --------------------------------------------------------------

    private function importFromSource(Harvester $harvester, $options, $limit = null)
    {
        //Build the options to send
        $harvestOptions = array();
        foreach($harvester->getOptions() as $opt => $optInfo) {
            $harvestOptions[$opt] = (isset($options[$opt])) 
                ? $options[$opt]
                : $optInfo['default'];
        }

        //Do it
        $harvester->harvest($harvestOptions, $limit);
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

        //Load the correct classes to do the clobber
        $cName   = $this->documentCollection->getRepository()->getClassName();
        $collObj = $this->documentCollection->getRepository()->getDocumentManager()->getDocumentCollection($cName);
        
        $result = $collObj->drop();

        if ($result['ok'] == 1) {
            $output->writeln("Dropped collection.");
        }
        else {
            $output->writeln("No collection to drop. Moving on...");
        }

        return true;
    }

    // --------------------------------------------------------------

    private function compileSources(InputInterface $input)
    {
        //If netiher -a or -s, fail
        if ( ! $input->getOption('auto') && ! $input->getOption('source')) {
            throw new RuntimeException('You must specify either a source with -s or auto with -a');
        }

        $sourceList = array();

        //Do the appropriate action depending on the source
        if ($input->getOption('source')) {

            $source = $input->getOption('source');
            if ( ! isset($this->harvesters[$source])) {
                throw new RuntimeException(sprintf("The source '%s' does not exist", $source));
            }

            return array($this->harvesters[$source]);   
        }
        else {
            //Load from auto... @TODO THIS
        }
    }    
}

/* EOF: RecordsHarvest.php */