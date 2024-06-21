<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Dotenv\Dotenv;

class ShowDatabaseInfoCommand extends Command
{
    protected static $defaultName = 'app:show-database-info';

    protected function configure()
    {
        $this
            ->setDescription('Affiche les informations de connexion à la base de données');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $dbUser = $_ENV['DB_USER']; // Remplacez DB_USER par la clé correspondante dans votre fichier .env
        $dbPassword = $_ENV['DB_PASSWORD'];
        $dbBase = $_ENV['DB_BASE'];

        // Affichage des informations
        $output->writeln("Informations de connexion à la base de données :");
        $output->writeln("Utilisateur : $dbUser");
        $output->writeln("Mot de passe : $dbPassword");
        $output->writeln("Base : $dbBase");

        return Command::SUCCESS;
    }
}
