<?php

namespace App\Service;

use App\Model\Genre;
use App\Model\Movie;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class MongoDBServiceTest extends TestCase
{
    protected $service; 
    protected $client;
    protected $denormalizer;

    public function setUp(): void 
    {
        $this->client =  $this->createMock(MovieDBApiClientInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);

        $this->service = new MovieDBService(
            $this->client, 
            $this->denormalizer,
            []
        );
    }

    public function testGetGenres() 
    {
        $genres = \json_decode(file_get_contents(__DIR__.'/JSON/genre.json'), true);
        $denormalizedGenre = array_map(function() { return new Genre(); }, $genres['genres']);

        $this->client->expects($this->once())
            ->method('requestApi')->with(Iri::GENRE_LIST_URL)
            ->willReturn($genres);

        $this->denormalizer->expects($this->once())
            ->method('denormalize')->with($genres['genres'], Genre::class.'[]', 'array')
            ->willReturn($denormalizedGenre);

        $this->service->getGenres();

        $this->assertCount(19, $denormalizedGenre);
        $this->assertInstanceOf(Genre::class, $denormalizedGenre[0]);
    }

    public function testGetTopRatedMovies() 
    {
        $movies = \json_decode(file_get_contents(__DIR__.'/JSON/movies.json'), true);
        $denormalizedMovies = array_map(function() { return new Movie(); }, $movies['results']);
        $this->client->expects($this->once())
            ->method('requestApi')->with(Iri::BEST_LIST_MOVIE_URL, ['page' => 1])
            ->willReturn($movies);

        $this->denormalizer->expects($this->any())
            ->method('denormalize')->with($movies['results'][0], Movie::class, 'array')
            ->willReturn($denormalizedMovies[0]);

        $this->service->getTopRatedMovies();
    }


}
