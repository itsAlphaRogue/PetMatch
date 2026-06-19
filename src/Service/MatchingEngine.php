<?php

namespace PetMatch\Service;

use PetMatch\Domain\BreedProfile;
use PetMatch\Domain\MatchResult;

class MatchingEngine {
    private string $breedsJsonPath;
    private array $weights;
    private array $maxPerAttr;
    private float $maxDist;

    public function __construct(string $breedsJsonPath = null) {
        $this->breedsJsonPath = $breedsJsonPath ?: __DIR__ . '/../../assets/breeds.json';
        
        $this->weights = [
            'living-space'        => 2.0,
            'activity-level'      => 2.5,
            'grooming-time'       => 1.5,
            'experience'          => 2.0,
            'children'            => 2.5,
            'budget'              => 1.5,
            'shedding'            => 2.0,   
            'noise'               => 2.0,   
            'other-pets'          => 2.0,  
            'alone-time'          => 2.0,  
            'climate'             => 2.5,  
        ];

        $this->maxPerAttr = [
            'living-space'   => 4,
            'activity-level' => 4,
            'grooming-time'  => 2,
            'experience'     => 2,
            'children'       => 2,
            'budget'         => 2,
            'shedding'       => 2,
            'noise'          => 2,
            'other-pets'     => 2,
            'alone-time'     => 2,
            'climate'        => 4,
        ];

        $this->maxDist = 0.0;
        foreach ($this->weights as $k => $w) {
            $this->maxDist += $w * $this->maxPerAttr[$k];
        }
    }

    /**
     * @return BreedProfile[]
     */
    public function loadBreedProfiles(): array {
        if (!file_exists($this->breedsJsonPath)) {
            return [];
        }
        $json = file_get_contents($this->breedsJsonPath);
        $breedsArray = json_decode($json, true);
        if (!is_array($breedsArray)) {
            return [];
        }

        $profiles = [];
        foreach ($breedsArray as $breedData) {
            $profiles[] = new BreedProfile($breedData);
        }
        return $profiles;
    }

    /**
     * Executes weighted Manhattan distance matching to calculate compatibility.
     * 
     * @param array $userVector
     * @return MatchResult[] Sorted by compatibility score descending
     */
    public function findMatches(array $userVector): array {
        $profiles = $this->loadBreedProfiles();
        $matches = [];

        foreach ($profiles as $profile) {
            $breedVector = $profile->toVector();
            $score = $this->calculateScore($userVector, $breedVector);
            $matches[] = new MatchResult($profile->getName(), $score);
        }

        usort($matches, fn($a, $b) => $b->getScore() <=> $a->getScore());
        return $matches;
    }

    public function calculateScore(array $userVector, array $breedVector): float {
        $distance = 0.0;
        foreach ($this->weights as $key => $weight) {
            $userVal = isset($userVector[$key]) ? (int)$userVector[$key] : 0;
            $breedVal = isset($breedVector[$key]) ? (int)$breedVector[$key] : 0;
            $distance += $weight * abs($userVal - $breedVal);
        }
        $rawScore = (1.0 / (1.0 + $distance / $this->maxDist)) * 100;
        return (float)number_format($rawScore, 2);
    }

    public function getWeights(): array {
        return $this->weights;
    }

    public function getMaxPerAttr(): array {
        return $this->maxPerAttr;
    }
}
