<?php 

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModifyDatabaseUrlCommand extends Command
{
    protected static $defaultName = 'app:modify-database-url';

    protected function configure()
    {
        $this
            ->setDescription('Modifie la variable DATABASE_URL dans le fichier .env')
            ->addArgument('url', InputArgument::REQUIRED, 'La nouvelle URL de la base de données');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newDatabaseUrl = $input->getArgument('url');
        $envFilePath = $this->getProjectDir() . '/.env';

        if (!file_exists($envFilePath)) {
            $output->writeln('<error>Le fichier .env n\'existe pas.</error>');
            return Command::FAILURE;
        }

        $envContent = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $key = 'DATABASE_URL';
        $keyFound = false;

        foreach ($envContent as &$line) {
            if (strpos($line, $key) === 0) {
                $line = "$key=\"$newDatabaseUrl\"";
                $keyFound = true;
                break;
            }
        }

        if (!$keyFound) {
            $envContent[] = "$key=\"$newDatabaseUrl\"";
        }

        file_put_contents($envFilePath, implode(PHP_EOL, $envContent) . PHP_EOL);

        $output->writeln('<info>La variable DATABASE_URL a été mise à jour avec succès.</info>');
        return Command::SUCCESS;
    }

    private function getProjectDir(): string
    {
        return __DIR__ . '/../..';
    }
}
