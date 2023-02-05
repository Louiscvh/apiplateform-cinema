<?php

namespace App\Command;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\HttpClientTrait;

#[AsCommand(
    name: 'fetchAndPutGenre',
    description: 'This command fetches genres data from The Movie DB API and stores it in the database using the Genre entities.',
)]
class FetchAndPutGenreCommand extends Command
{
    use HttpClientTrait;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }
    protected function configure(): void
    {
        $this
            ->setDescription('This command fetches genres data from The Movie DB API and stores it in the database using the Genre entities.')
            ->setHelp('Run this command to add genre to your database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();
        $apiKey = '9bb5e5fad7f486701ebd88410f1eec7a';

        $response = $client->request("GET", "https://api.themoviedb.org/3/genre/movie/list?api_key={$apiKey}&language=fr-FR&page=1");
        if (200 === $response->getStatusCode()) {
            $data = $response->toArray();

            foreach ($data['genres'] as $genreData) {
                $genre = new Genre();
                $genre->setName($genreData['name']);

                $this->entityManager->persist($genre);
            }

            $this->entityManager->flush();
            $output->writeln('Genres fetched are stored in the database');
        } else {
            $output->writeln(sprintf('Failed to fetch data: %s', $response->getStatusCode()));
        }

        return 0;
    }
}
