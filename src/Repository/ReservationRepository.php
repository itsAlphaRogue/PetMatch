<?php

namespace PetMatch\Repository;

use PetMatch\Database;
use PetMatch\Domain\Reservation;
use PetMatch\Domain\Pet;
use PetMatch\Domain\User;

class ReservationRepository {
    private \mysqli $db;
    private PetRepository $petRepo;
    private UserRepository $userRepo;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->petRepo = new PetRepository();
        $this->userRepo = new UserRepository();
    }

    public function findPending(): array {
        $result = $this->db->query("SELECT * FROM adoption_requests WHERE status = 'Pending'");
        $reservations = [];
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $this->hydrate($row);
        }
        return $reservations;
    }

    public function findByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM adoption_requests WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservations = [];
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $this->hydrate($row);
        }
        return $reservations;
    }

    public function create(int $petId, int $userId): bool {
        $stmt = $this->db->prepare("INSERT INTO adoption_requests (pet_id, user_id, status) VALUES (?, ?, 'Pending')");
        $stmt->bind_param("ii", $petId, $userId);
        return $stmt->execute();
    }

    public function updateStatus(int $petId, string $status): bool {
        $stmt = $this->db->prepare("UPDATE adoption_requests SET status = ? WHERE pet_id = ? AND status = 'Pending'");
        $stmt->bind_param("si", $status, $petId);
        return $stmt->execute();
    }

    private function hydrate(array $row): Reservation {
        $pet = $this->petRepo->findById((int)$row['pet_id']);
        $user = $this->userRepo->findById((int)$row['user_id']);
        return new Reservation(
            (int)$row['id'],
            (int)$row['pet_id'],
            (int)$row['user_id'],
            $row['status'],
            $row['requested_at'],
            $pet,
            $user
        );
    }
}
