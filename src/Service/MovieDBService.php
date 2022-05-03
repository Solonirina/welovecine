<?php

namespace App\Service;

use App\Model\Genre;
use App\Model\Movie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use function Symfony\Component\String\u;

class MovieDBService
{
    private const SITE_YOUTUBE = 'youtube';
    
    private $client;
    private $denormalizer;
    private $players;

    public function __construct(MovieDBApiClientInterface $client, DenormalizerInterface $denormalizer, array $players)
    {
        $this->client = $client;
        $this->denormalizer = $denormalizer;
        $this->players = $players;
    }

    public function getGenres(): array 
    {
        $responses = $this->client->requestApi(Iri::GENRE_LIST_URL);

        return $this->denormalizer->denormalize($responses['genres'] ?? [], Genre::class.'[]', 'array');
    }

    public function getTopRatedMovies(int $page = 1): array
    {
        $responses = $this->client->requestApi(Iri::BEST_LIST_MOVIE_URL, ['page' => $page]);
        
        return array_map(function($result) {
            $movie = $this->denormalizer->denormalize($result ?? [], Movie::class, 'array');
            $movie->setGenresIds($result['genre_ids'] ?? []);
            
            return $movie;

        }, $responses['results']);   
    }

    public function getBestMovieBy(?Movie $movie): Movie
    {
        if (is_null($movie)) {
            throw new NotFoundHttpException('Movie not found!');
        }

        $movie->setVideoUrl($this->getVideoUrl($movie->getId()));

        return $movie;
    }

    public function getMovieDetail(int $movieId) 
    {
        $responses = $this->client->requestApi(str_replace('{movie_id}', $movieId, Iri::GET_MOVIE_DETAIL));

        return array_merge($responses, ['videoUrl' => $this->getVideoUrl($movieId)]);
    }

    public function filteredMovieBy(array $genres) 
    {
        $result = [];
        $page = 1;

        if (empty(array_filter($genres))) {
            return $this->getTopRatedMovies($page);
        }
        
        while(\count($result) <= 20 && $page <= 1000) {
            $tmpMovies = $this->getTopRatedMovies($page);
            /** @var Movie $movie */
            foreach($tmpMovies as $movie) {
                if (!empty($commonGendersIds = $movie->getGenreIds())  && !empty(array_intersect($genres, $commonGendersIds))) {
                    $result[] = $movie;
                }
            }

            $page++;
        }
        
        return $result;
    }

    public function searchMovies(string $query) 
    {
        if (!empty($query)) {
            $responses = $this->client->requestApi(Iri::SEARCH_MOVIES, ['query' => $query]);
            return $this->denormalizer->denormalize($responses['results'] ?? [], Movie::class.'[]', 'array');
        }

        return $this->getTopRatedMovies();
    }

    private function getVideoUrl(int $movieId): ?string 
    {
        $responses = $this->client->requestApi(str_replace('{movie_id}', $movieId, Iri::GET_VIDEO_URL));
        $results = $responses['results'] ?? [];

        if (empty($results)) {
            return null;
        }

        $video = reset($results);

        return sprintf('%s/%s',
                ((string) u($video['site'] ?? '')->lower() === self::SITE_YOUTUBE) ? $this->players['youtube'] : $this->players['moviedb'], 
                $video['key'] ?? ''
        );
    }
}
