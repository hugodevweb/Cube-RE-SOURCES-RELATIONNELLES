<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Filesystem\Filesystem;

class MigrateDatabaseCommand extends Command
{
    protected static $defaultName = 'app:migrate-database';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $filesystem = new Filesystem();

        // $io->section('Deleting all migration files...');
        // $migrationsDir = __DIR__ . '/../../migrations';

        // if ($filesystem->exists($migrationsDir)) {
        //     $filesystem->remove(glob($migrationsDir . '/*'));
        //     $io->success('All migration files deleted.');
        // } else {
        //     $io->warning('Migrations directory does not exist.');
        // }

        $io->section('Make migration...');
        $process = new Process(['php', 'bin/console', 'make:migration']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $io->success('Make migration success.');

        $io->section('Migration...');
        $process = new Process(['php', 'bin/console', 'doctrine:migrations:migrate', '--no-interaction', '--allow-no-migration']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $io->success('Migration success.');

        return Command::SUCCESS;
    }
}
