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
        //Console app
        $this->consoleApp = new ConsoleApp('Citagora');

        //Load the commands
        $this->addCommand(new Command\Test());
        //more here...

        //Run it
        return $this->consoleApp->run();        
    }

    // --------------------------------------------------------------

    private function addCommand(CitagoraCommand $command)
    {
        $command->connect($this);
        $this->consoleApp->add($command);
    }
}

/* EOF: CliApp.php */