<?php

declare(strict_types=1);

namespace App\Command;

use App\Helper\CallPersister;
use App\Helper\CsvReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportFromCsvCommand extends Command
{
    protected static $defaultName = 'app:import-ticket-call';

    private CsvReader $csvReader;

    private CallPersister $callPersister;

    public function __construct(CsvReader $csvReader, CallPersister $callPersister) 
    {
        parent::__construct();
        $this->csvReader = $csvReader;
        $this->callPersister = $callPersister;
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'path csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!file_exists($input->getArgument('file'))) {
            $output->writeln(sprintf('%s file not existing', $input->getArgument('file')));
            return Command::FAILURE;
        }

        $progressBar = new ProgressBar($output);

        $output->writeln('Begin retrieve data from csv: '.$input->getArgument('file'));
        $data = $this->csvReader->getData($input->getArgument('file'));

        $output->writeln('Begin import in database');
        $inserted = $this->callPersister->save($data, $progressBar);

        $progressBar->finish();

        $output->writeln(sprintf('%d tickets call were imported', $inserted));

        return Command::SUCCESS;
    }
}