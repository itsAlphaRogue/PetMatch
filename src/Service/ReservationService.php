<?php

namespace PetMatch\Service;

use PetMatch\Repository\ReservationRepository;
use PetMatch\Repository\PetRepository;

class ReservationService {
    private ReservationRepository $reservationRepo;
    private PetRepository $petRepo;

    public function __construct() {
        $this->reservationRepo = new ReservationRepository();
        $this->petRepo = new PetRepository();
    }

    public function createReservation(int $petId, int $userId): bool {
        $res = $this->reservationRepo->create($petId, $userId);
        if ($res) {
            return $this->petRepo->updateStatus($petId, 'Reserved');
        }
        return false;
    }

    public function approveReservation(int $petId): bool {
        $res = $this->reservationRepo->updateStatus($petId, 'Approved');
        if ($res) {
            return $this->petRepo->updateStatus($petId, 'Adopted');
        }
        return false;
    }

    public function rejectReservation(int $petId): bool {
        $res = $this->reservationRepo->updateStatus($petId, 'Rejected');
        if ($res) {
            return $this->petRepo->updateStatus($petId, 'Available');
        }
        return false;
    }

    public function getPendingReservations(): array {
        return $this->reservationRepo->findPending();
    }

    public function getReservationsByUserId(int $userId): array {
        return $this->reservationRepo->findByUserId($userId);
    }
}
