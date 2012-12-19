<?php

namespace Citagora\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Silex\Application;
use RuntimeException, InvalidArgumentException;
use Console_Table;


/**
 * Document Harvest Command
 */
class DocumentSources extends CommandAbstract
{
    /** 
     * @var array  Array of Harvester Objects
     */
    private $harvesters;

    // --------------------------------------------------------------

    public function __construct(array $harvesters)
    {
        parent::__construct();
        $this->harvesters = $harvesters;
    }

    // --------------------------------------------------------------

    protected function configure()
    {
        $this->setName('docs:sources');
        $this->setDescription('Harvest Records from a configured sources');
        $this->addArgument('source', InputArgument::OPTIONAL, 'Which source (leave empty for summary)', null);
    }

    // --------------------------------------------------------------

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch($input->getArgument('source')) {
            case null:
                return $this->listAllSources($input, $output);
            default:
                return $this->listSingleSource($input->getArgument('source'), $input, $output);
        }
    }    

    // --------------------------------------------------------------

    protected function listSingleSource($name, $input, $output)
    {
        if ( ! isset($this->harvesters[$name])) {
            throw new InvalidArgumentException("Harvester does not exist: " . $name);            
        }

        $harvester = $this->harvesters[$name];
        $options   = $harvester->getOptions();

        $output->writeln("\nHarvester: " . $name);
        $output->writeln($harvester->getName() . ' - ' . $harvester->getDescription());

        if (count($options) > 0) {

            $table = new Console_Table();
            $table->setHeaders(array('Option', 'Default', 'Description'));

            foreach($options as $optname => $items) {
                $table->addRow(array($optname, $items['default'], $items['description']));
            }

            $output->writeln("\n" . $table->getTable());
        }
        else {
            $output->writeln("This harvester has no options");
        }    
    }
   
    // --------------------------------------------------------------

    protected function listAllSources($input, $output)
    {
        if (count($this->harvesters) > 0) {

            $table = new Console_Table();
            $table->setHeaders(array('Name', 'Description', 'Has Options?'));

            foreach($this->harvesters as $harvester) {

                $numOptions = count($harvester->getOptions());

                $table->addRow(array(
                    $harvester->getName(),
                    $harvester->getDescription(),
                    ($numOptions > 0) ? sprintf('Yes (%s)', $numOptions) : "No"
                ));
            }

            $output->writeln("Available Harvesters:");
            $output->writeln($table->getTable());
        }
        else {
            $output->writeln("No harvesters exist");
        }
    }
}

/* EOF: DocumentSources.php */