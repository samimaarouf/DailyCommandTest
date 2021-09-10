<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;


class RunCommand
{
    public function __construct(KernelInterface $kernel){
        $this->kernel = $kernel;
    }

    public function run()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:update-daily',
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
    }
}
