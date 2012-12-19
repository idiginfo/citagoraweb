<?php

namespace Citagora;
use Symfony\Component\Console\Application as ConsoleApp;
use Citagora\Command\CommandAbstract as CitagoraCommand;
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

    protected function loadCliLibraries()
    {
        $app =& $this;

        //Harvesters
        $this['harvesters'] = $this->share(function($app) {

            $harvesters = array(
                new Harvester\DummyRecords($app['em'])
                //add harvesters...
            );
            
            foreach($harvesters as $k => $cls) {
                $harvesters[$cls->getName()] = $cls;
                unset($harvesters[$k]);
            }
            return $harvesters;
        });
    }    
}

/* EOF: CliApp.php */