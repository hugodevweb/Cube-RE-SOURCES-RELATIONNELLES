<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SetupDatabaseCommand extends Command
{
    protected static $defaultName = 'app:setup-database';
    protected static $defaultDescription = 'Setup the database in a Docker container and run migrations';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__.'/../../.env');

        $dbUser = $_ENV['DB_USER']; // Remplacez DB_USER par la clé correspondante dans votre fichier .env
        $dbPassword = $_ENV['DB_PASSWORD'];
        $dbBase = $_ENV['DB_BASE'];

        $io = new SymfonyStyle($input, $output);

        // 1. Step: Write docker-compose.yml
        $io->section('Writing compose.yml...');
        $dockerComposeContent = sprintf('
version: \'3.8\'
networks:
  monitoring:
    driver: bridge
    ipam:
      config:
        - subnet: 192.168.208.0/24
  app-network:
    driver: bridge
    ipam:
      config:
        - subnet: 192.168.192.0/24

services:
  php:
    build:
      context: .
      dockerfile: Dockerfile
    volumes:
      - .:/var/www/html
    ports:
      - "8888:80"
    networks:
      app-network:
        ipv4_address: 192.168.192.43

  db:
    image: mariadb:latest
    environment:
      MYSQL_ROOT_PASSWORD: "rootcube"
      MYSQL_DATABASE: "cube"
    ports:
      - "3306:3306"
    networks:
      app-network:
        ipv4_address: 192.168.192.250
      monitoring:
        ipv4_address: 192.168.208.100

  phpmyadmin:
    image: phpmyadmin:latest
    ports:
      - "8080:80"
    networks:
      app-network:
        ipv4_address: 192.168.192.4
', $dbPassword, $dbBase);

        file_put_contents('compose.yml', $dockerComposeContent);
        $io->success('compose.yml written successfully.');

        // 2. Step: Start Docker container
        $io->section('Starting Docker container...');
        $process = new Process(['docker-compose', 'up', '-d']);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
            
        $io->success('Docker container started successfully.');

        return Command::SUCCESS;
    }
}
