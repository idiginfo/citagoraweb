<?php

namespace Citagora\Cli;

use Citagora\App as CitagoraApp;
use Symfony\Component\Console\Application as ConsoleApp;
use Citagora\Cli\Command\CommandAbstract as CitagoraCommand;
use Citagora\Common\Harvester\HarvesterAbstract;
use RuntimeException, Exception;

class App extends CitagoraApp
{
    /** 
     * @var Symfony\Component\Console\Application
     */
    private $consoleApp;

    // --------------------------------------------------------------

    public function exec()
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