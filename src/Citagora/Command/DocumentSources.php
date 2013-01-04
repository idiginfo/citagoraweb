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
    private $dataSources;

    // --------------------------------------------------------------

    protected function configure()
    {
        $this->setName('docs:sources');
        $this->setDescription('List available Data Sources');
        $this->addArgument('source', InputArgument::OPTIONAL, 'Which source (leave empty for summary)', null);
    }

    // --------------------------------------------------------------

    public function init(Application $app)
    {
        $this->dataSources = $app['data_sources'];
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
        if ( ! isset($this->dataSources[$name])) {
            throw new InvalidArgumentException("Data Source does not exist: " . $name);            
        }

        $dataSource = $this->dataSources[$name];
        $params     = $dataSource->getAvailableParams();

        $output->writeln("\ndataSource: " . $name);
        $output->writeln($dataSource->getSlug() . ' - ' . $dataSource->getName());

        if (count($params) > 0) {

            $table = new Console_Table();
            $table->setHeaders(array('Option', 'Default', 'Description'));

            foreach($params as $opt) {
                $table->addRow(array($opt->name, $opt->default ?: "<none>", $opt->description));
            }

            $output->writeln("\n" . $table->getTable());
        }
        else {
            $output->writeln("This Data Source has no params");
        }    
    }
   
    // --------------------------------------------------------------

    protected function listAllSources($input, $output)
    {
        if (count($this->dataSources) > 0) {

            $table = new Console_Table();
            $table->setHeaders(array('Name', 'Description', 'Has Options?'));

            foreach($this->dataSources->keys() as $k) {

                $dataSource = $this->dataSources[$k];
                $numParams = count($dataSource->getAvailableParams());

                $table->addRow(array(
                    $dataSource->getSlug(),
                    $dataSource->getName(),
                    ($numParams > 0) ? sprintf('Yes (%s)', $numParams) : "No"
                ));
            }

            $output->writeln("Available Data Sources:");
            $output->writeln($table->getTable());
        }
        else {
            $output->writeln("No Data Sources exist");
        }
    }
}

/* EOF: DocumentSources.php */