<?php

namespace Citagora\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Test extends CommandAbstract
{
    protected function configure()
    {
        $this->setName('test');
        $this->setDescription('Test Command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Hello World");
    }
}

/* EOF: TestCommmand.php */