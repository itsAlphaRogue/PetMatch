<?php

namespace PetMatch\Service;

use PetMatch\Domain\Pet;
use PetMatch\Repository\PetRepository;

class PetService {
    private PetRepository $petRepo;

    public function __construct() {
        $this->petRepo = new PetRepository();
    }

    public function getPetById(int $id): ?Pet {
        return $this->petRepo->findById($id);
    }

    public function getAllPets(): array {
        return $this->petRepo->getAll();
    }

    public function getAvailablePets(int $limit = 0): array {
        return $this->petRepo->getAvailable($limit);
    }

    public function filterPets(?string $age, ?string $gender, ?int $breedId): array {
        return $this->petRepo->findFiltered($age, $gender, $breedId);
    }

    public function searchPets(?string $name, ?int $breedId, ?string $status, ?string $age, ?string $gender): array {
        return $this->petRepo->search($name, $breedId, $status, $age, $gender);
    }

    public function createPet(string $name, ?int $breedId, string $age, string $gender, string $description, string $image): bool {
        $pet = new Pet(null, $name, $breedId, $age, $gender, 'Available', $description, $image);
        return $this->petRepo->save($pet);
    }

    public function updatePet(int $id, string $name, ?int $breedId, string $age, string $gender, string $status, string $description, string $image): bool {
        $pet = new Pet($id, $name, $breedId, $age, $gender, $status, $description, $image);
        return $this->petRepo->update($pet);
    }

    public function updatePetStatus(int $id, string $status): bool {
        return $this->petRepo->updateStatus($id, $status);
    }
}
