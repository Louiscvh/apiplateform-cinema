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
    name: 'fetchAndPutData',
    description: 'This command fetches movie data from The Movie DB API and stores it in the database using the Movie entities.',
)]
class FetchAndPutMovieCommand extends Command
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
            ->setDescription('This command fetches movie data from The Movie DB API and stores it in the database using the Movie entities.')
            ->setHelp('Run this command to add popular movies to your database.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();
        $apiKey = '9bb5e5fad7f486701ebd88410f1eec7a';

        $response = $client->request("GET", "https://api.themoviedb.org/3/movie/popular?api_key={$apiKey}&language=fr-FR&page=1");
        if (200 === $response->getStatusCode()) {
            $data = $response->toArray();

            foreach ($data['results'] as $movieData) {
                $movie = new Movie();
                $movie->setName($movieData['original_title']);
                $movie->setPoster($movieData['poster_path']);
                $movie->setAdult($movieData['adult']);

                foreach ($movieData['genre_ids'] as $genreId) {
                    $genre = $this->entityManager->getRepository(Genre::class)->find($genreId);
                    if ($genre === null) {
                        continue;
                    }
                    $movie->addGenre($genre);

                }

                $this->entityManager->persist($movie);
            }

            $this->entityManager->flush();
            $output->writeln('Movies fetched are stored in the database');
        } else {
            $output->writeln(sprintf('Failed to fetch data: %s', $response->getStatusCode()));
        }

        return 0;
    }
}
