<?php

namespace PetMatch\Domain;

class MatchResult {
    private string $breedName;
    private float $score;

    public function __construct(string $breedName, float $score) {
        $this->breedName = $breedName;
        $this->score = $score;
    }

    public function getBreedName(): string {
        return $this->breedName;
    }

    public function getScore(): float {
        return $this->score;
    }

    public function getCompatibilityLabel(): array {
        $s = $this->score;
        if ($s >= 90) return ['Exceptional match', 'bg-green-600',  'text-white'];
        if ($s >= 80) return ['Great match',        'bg-green-500',  'text-white'];
        if ($s >= 70) return ['Good match',         'bg-green-400',  'text-green-900'];
        if ($s >= 60) return ['Decent match',       'bg-yellow-300', 'text-yellow-900'];
        return             ['Low compatibility',   'bg-red-200',    'text-red-900'];
    }
}
