<?php

namespace App\Command;

use App\Service\DailyUserBuyGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as NormalizerObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UpdateDailyUserBuyCommand extends Command
{
    protected static $defaultName = "app:update-daily";
    private $dailyUserBuyGenerator;

    public function __construct($projectDir, DailyUserBuyGenerator $dailyUserBuyGenerator)
    {
        $this->dailyUserBuyGenerator = $dailyUserBuyGenerator;
        $this->projectDir = $projectDir;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription("Updating Daily User Buy");
    }

    //Run the command app:update-daily
    //Herited Method
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $inputFile = $this->projectDir . '/public/resultsusers/resultats_users.csv';
        $userProducts = $this->getData($inputFile);
        $this->dailyUserBuyGenerator->create($userProducts);
        return 0;
    }

    //@input the File Directory, here a CSV File. Change Method to put new usecases
    //@return array of data 
    public function getData($inputFile) : array
    {
        //TODO if evolution when we want to add new type of file, add switch to specify the condition and the method to invoke
        //Other possibility, create an interface with getData and a class associated
        return $this->getCsvRowsAsArrays($inputFile);
    }

    //@input the File Directory for a CSV File
    //@return array of data
    public function getCsvRowsAsArrays($inputFile) : array
    {
        $decoder = new Serializer([new NormalizerObjectNormalizer()], [new CsvEncoder()]);

        return $decoder->decode(file_get_contents($inputFile), "csv", [CsvEncoder::DELIMITER_KEY => ';']);
    }

    
}
