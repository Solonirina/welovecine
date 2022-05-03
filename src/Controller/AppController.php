<?php

namespace App\Controller;

use App\Service\MovieDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    private $service;

    public function __construct(MovieDBService $service)
    {
        $this->service = $service;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $topRatedMovies = $this->service->getTopRatedMovies();

        return $this->render('index.html.twig', [
            'genres' => $this->service->getGenres(),
            'topRatedMovies' => $topRatedMovies,
            'bestMovie' => $this->service->getBestMovieBy($topRatedMovies[0])
        ]);
    }

    #[Route('/detail/{movieId}', name: 'movie_detail')]
    public function detail(int $movieId): JsonResponse
    {
        $details = $this->service->getMovieDetail($movieId);

        return $this->json($details);
    }

    #[Route('/search', name: 'search_movies')]
    public function search(Request $request): JsonResponse
    {
        $results = $this->service->searchMovies($request->request->get('query'));
        return $this->json([
            'cards' => $this->renderView('partial/card.html.twig', [
                'movies' => $results
            ])
        ]);
    }

    #[Route('/filters', name: 'filters_movies')]
    public function filters(Request $request): JsonResponse
    {
        $results = $this->service->filteredMovieBy($request->request->get('genres', []));

        return $this->json([
            'cards' => $this->renderView('partial/card.html.twig', [
                'movies' => $results
            ])
        ]);
    }
}
