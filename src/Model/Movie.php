<?php

namespace App\Model;

class Movie
{
    private int $id;
    
    private ?string $posterPath;

    private ?string $name;
    
    private ?bool $adult;

    private string $overview;

    private string $releaseDate;

    private array $genreIds;
    
    private string $originalTitle;

    private string $originalLanguage;

    private string $title;
    
    private ?string $backdropPath;
    
    private mixed $popularity;

    private float $videoAverage;
    
    private int $voteCount;

    private ?string $videoUrl;
    
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
    
    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }
    
    public function setPosterPath(?string $posterPath): self
    {
        $this->posterPath = $posterPath;

        return $this;
    }
    
    public function getAdult(): ?bool
    {
        return $this->adult;
    }
     
    public function setAdult(?bool $adult): self
    {
        $this->adult = $adult;

        return $this;
    }

    public function getOverview(): string
    {
        return $this->overview;
    }
    
    public function setOverview(string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }
    
    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }
    
    public function setReleaseDate(string $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }
    
    public function getGenreIds(): array
    {
        return $this->genreIds;
    }
    
    public function setGenresIds(array $genreIds): self
    {
        $this->genreIds = $genreIds;

        return $this;
    }
    
    public function getOriginalTitle(): string
    {
        return $this->originalTitle;
    }
    
    public function setOriginalTitle(string $originalTitle): self
    {
        $this->originalTitle = $originalTitle;

        return $this;
    }

    public function getOriginalLanguage(): string
    {
        return $this->originalLanguage;
    }
    
    public function setOriginalLanguage(string $originalLanguage): self
    {
        $this->originalLanguage = $originalLanguage;

        return $this;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
    
    public function getBackdropPath(): ?string
    {
        return $this->backdropPath;
    }
    
    public function setBackdropPath(?string $backdropPath): self
    {
        $this->backdropPath = $backdropPath;

        return $this;
    }
    
    public function getPopularity(): mixed
    {
        return $this->popularity;
    }
    
    public function setPopularity(mixed $popularity): self
    {
        $this->popularity = $popularity;

        return $this;
    }
    
    public function getVideoAverage(): float
    {
        return $this->videoAverage;
    }
    
    public function setVideoAverage(float $videoAverage): self
    {
        $this->videoAverage = $videoAverage;

        return $this;
    }
    
    public function getVoteCount(): int
    {
        return $this->voteCount;
    }
     
    public function setVoteCount(int $voteCount): self
    {
        $this->voteCount = $voteCount;

        return $this;
    }
    
    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }
    
    public function setVideoUrl(?string $videoUrl)
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }
}
