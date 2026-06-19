<?php

namespace PetMatch\Domain;

class Reservation {
    private ?int $id;
    private int $petId;
    private int $userId;
    private string $status;
    private string $requestedAt;
    
    private ?Pet $pet;
    private ?User $user;

    public function __construct(
        ?int $id,
        int $petId,
        int $userId,
        string $status,
        string $requestedAt,
        ?Pet $pet = null,
        ?User $user = null
    ) {
        $this->id = $id;
        $this->petId = $petId;
        $this->userId = $userId;
        $this->status = $status;
        $this->requestedAt = $requestedAt;
        $this->pet = $pet;
        $this->user = $user;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getPetId(): int {
        return $this->petId;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getRequestedAt(): string {
        return $this->requestedAt;
    }

    public function getPet(): ?Pet {
        return $this->pet;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function getStatusColorClass(): string {
        if ($this->status === 'Approved') {
            return 'text-green-600';
        } elseif ($this->status === 'Pending') {
            return 'text-yellow-600';
        } else {
            return 'text-red-600';
        }
    }
}
