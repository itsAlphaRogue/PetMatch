<?php

namespace PetMatch\Domain;

class BreedProfile {
    private string $name;
    private int $spaceRequirement;
    private int $activityLevel;
    private int $groomingNeeds;
    private int $trainability;
    private int $goodWithChildren;
    private int $maintenanceCost;
    private int $sheddingLevel;
    private int $noiseLevel;
    private int $goodWithPets;
    private int $aloneTimeTolerance;
    private int $climateTolerance;

    public function __construct(array $data) {
        $this->name = $data['name'];
        $this->spaceRequirement = (int)$data['space_requirement'];
        $this->activityLevel = (int)$data['activity_level'];
        $this->groomingNeeds = (int)$data['grooming_needs'];
        $this->trainability = (int)$data['trainability'];
        $this->goodWithChildren = (int)$data['good_with_children'];
        $this->maintenanceCost = (int)$data['maintenance_cost'];
        $this->sheddingLevel = (int)$data['shedding_level'];
        $this->noiseLevel = (int)$data['noise_level'];
        $this->goodWithPets = (int)$data['good_with_pets'];
        $this->aloneTimeTolerance = (int)$data['alone_time_tolerance'];
        $this->climateTolerance = (int)$data['climate_tolerance'];
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSpaceRequirement(): int {
        return $this->spaceRequirement;
    }

    public function getActivityLevel(): int {
        return $this->activityLevel;
    }

    public function getGroomingNeeds(): int {
        return $this->groomingNeeds;
    }

    public function getTrainability(): int {
        return $this->trainability;
    }

    public function getGoodWithChildren(): int {
        return $this->goodWithChildren;
    }

    public function getMaintenanceCost(): int {
        return $this->maintenanceCost;
    }

    public function getSheddingLevel(): int {
        return $this->sheddingLevel;
    }

    public function getNoiseLevel(): int {
        return $this->noiseLevel;
    }

    public function getGoodWithPets(): int {
        return $this->goodWithPets;
    }

    public function getAloneTimeTolerance(): int {
        return $this->aloneTimeTolerance;
    }

    public function getClimateTolerance(): int {
        return $this->climateTolerance;
    }

    public function toVector(): array {
        return [
            'living-space'        => $this->spaceRequirement,
            'activity-level'      => $this->activityLevel,
            'grooming-time'       => $this->groomingNeeds,
            'experience'          => $this->trainability,
            'children'            => $this->goodWithChildren,
            'budget'              => $this->maintenanceCost,
            'shedding'            => $this->sheddingLevel,
            'noise'               => $this->noiseLevel,
            'other-pets'          => $this->goodWithPets,
            'alone-time'          => $this->aloneTimeTolerance,
            'climate'             => $this->climateTolerance,
        ];
    }
}
