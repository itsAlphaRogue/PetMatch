<?php

namespace PetMatch\Domain;

class Pet {
    private ?int $id;
    private string $name;
    private ?int $breedId;
    private ?string $breedName;
    private string $age;
    private string $gender;
    private string $status;
    private string $description;
    private string $image;

    public function __construct(
        ?int $id,
        string $name,
        ?int $breedId,
        string $age,
        string $gender,
        string $status,
        string $description,
        string $image,
        ?string $breedName = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->breedId = $breedId;
        $this->age = $age;
        $this->gender = $gender;
        $this->status = $status;
        $this->description = $description;
        $this->image = $image;
        $this->breedName = $breedName;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getBreedId(): ?int {
        return $this->breedId;
    }

    public function getBreedName(): ?string {
        return $this->breedName;
    }

    public function setBreedName(?string $breedName): void {
        $this->breedName = $breedName;
    }

    public function getAge(): string {
        return $this->age;
    }

    public function getGender(): string {
        return $this->gender;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getImage(): string {
        return $this->image;
    }
    
    public function getDisplayStatus(): string {
        if ($this->status === 'Available') {
            return 'Available for adoption';
        } elseif ($this->status === 'Reserved') {
            return 'Currently reserved';
        } else {
            return 'Adopted';
        }
    }

    public function getStatusColors(): string {
        $status = $this->getDisplayStatus();
        if ($status === 'Available for adoption') {
            return 'bg-green-400/80 text-green-900';
        } elseif ($status === 'Currently reserved') {
            return 'bg-yellow-400/30 text-yellow-600';
        } else {
            return 'bg-red-400/30 text-red-600';
        }
    }

    public function getReserveButtonClass(): string {
        $status = $this->getDisplayStatus();
        if ($status === 'Available for adoption') {
            return 'bg-green-400 hover:bg-green-300 active:bg-green-600 cursor-pointer';
        } else {
            return 'bg-neutral-400 cursor-not-allowed pointer-events-none';
        }
    }
}
