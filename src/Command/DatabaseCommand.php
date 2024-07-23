<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabaseCommand extends Command
{
    protected static $defaultName = 'app:database';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Commande
        set_time_limit(300);

        $io = new SymfonyStyle($input, $output);

        $process = new Process(['clear']);
        $process->run();

        $output->writeln([
            '==================',
            '|DATABASE Creator|',
            '==================',
            '',
        ]);

        $io->section('Setup Database...');

        $process4 = new Process(['php', 'bin/console', 'app:modify-database-url', 'mysql://root:rootcube@192.168.192.250:3306/cube']);
        $process4->run();

        $process = new Process(['php', 'bin/console', 'app:setup-database']);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $io->success('Setup database success');

        $output->writeln([
            '==================',
            '|      WAIT      |',
            '==================',
        ]);
        
        sleep(15);

        $process2 = new Process(['php', 'bin/console', 'app:modify-database-url', 'mysql://root:rootcube@localhost:3306/cube']);
        $process2->run();

        $io->section('Make migration...');

        $process3 = new Process(['php', 'bin/console', 'app:migrate-database']);
        $process3->run();

        if (!$process3->isSuccessful()) {
            echo 'Migration database failed';
            throw new ProcessFailedException($process3);
        }
        echo 'Migration database success';

        $process4 = new Process(['php', 'bin/console', 'app:modify-database-url', 'mysql://root:rootcube@192.168.192.250:3306/cube']);
        $process4->run();
        
        $io->success('Migration database success');

        $output->writeln([
            '==================',
            '|       END      |',
            '==================',
            '',
        ]);

        return Command::SUCCESS;
    }
}
