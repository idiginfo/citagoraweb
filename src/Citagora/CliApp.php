<?php

namespace Citagora;
use Symfony\Component\Console\Application as ConsoleApp;
use Citagora\Command\CommandAbstract as CitagoraCommand;
use Citagora\Harvester\HarvesterAbstract;
use RuntimeException, Exception;

class CliApp extends App
{
    /** 
     * @var Symfony\Component\Console\Application
     */
    private $consoleApp;

    // --------------------------------------------------------------

    public function run()
    {
        //Load Libraries
        $this->loadCliLibraries();

        //Console app
        $this->consoleApp = new ConsoleApp('Citagora');

        //Add Document Harvest Command
        $docColl = $this['em']->getCollection('Document\Document');
        $this->addCommand(new Command\DocumentHarvest($docColl, $this['harvesters']));

        //Add Documetn Sources Command
        $this->addCommand(new Command\DocumentSources($this['harvesters']));


        //Run it
        return $this->consoleApp->run();        
    }

    // --------------------------------------------------------------

    private function addCommand(CitagoraCommand $command)
    {
        $command->connect($this);
        $this->consoleApp->add($command);
    }

    // --------------------------------------------------------------

    private function addHarvester(HarvesterAbstract $harvester)
    {
        //Prep array
        if ( ! isset($this['harvesters'])) {
            $this['harvesters'] = array();
        }

        //Add it
        $this['harvesters'] = array_merge(
            $this['harvesters'],
            array($harvester->getName() => $harvester)
        );
    }

    // --------------------------------------------------------------

    protected function loadCliLibraries()
    {
        $app =& $this;

        //Harvesters
        $this->addHarvester(new Harvester\DummyRecords($this['em']));
        $this->addHarvester(new Harvester\Arxiv(
            $this['em'],
            new \Phpoaipmh\Endpoint(new \Phpoaipmh\Client('', new \Phpoaipmh\Http\Guzzle()))
        ));
    }    
}

/* EOF: CliApp.php */