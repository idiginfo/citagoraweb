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

        //Add Commands
        $this->addCommand(new Command\DocumentHarvest());
        $this->addCommand(new Command\DocumentSources());


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

        //Load stuff here...
    }
}

/* EOF: CliApp.php */