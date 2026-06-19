<?php

namespace PetMatch\Repository;

use PetMatch\Database;
use PetMatch\Domain\Pet;

class PetRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?Pet {
        $stmt = $this->db->prepare("SELECT p.*, b.name as breed_name FROM pets p LEFT JOIN breeds b ON p.breed_id = b.id WHERE p.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $this->hydrate($row);
        }
        return null;
    }

    public function getAll(): array {
        $result = $this->db->query("SELECT p.*, b.name as breed_name FROM pets p LEFT JOIN breeds b ON p.breed_id = b.id");
        $pets = [];
        while ($row = $result->fetch_assoc()) {
            $pets[] = $this->hydrate($row);
        }
        return $pets;
    }

    public function getAvailable(int $limit = 0): array {
        $sql = "SELECT p.*, b.name as breed_name FROM pets p LEFT JOIN breeds b ON p.breed_id = b.id WHERE p.status = 'Available'";
        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $result = $this->db->query($sql);
        $pets = [];
        while ($row = $result->fetch_assoc()) {
            $pets[] = $this->hydrate($row);
        }
        return $pets;
    }

    public function findFiltered(?string $age, ?string $gender, ?int $breedId): array {
        $query = "SELECT p.*, b.name as breed_name FROM pets p LEFT JOIN breeds b ON p.breed_id = b.id WHERE 1 = 1";
        $types = "";
        $params = [];

        if (!empty($age)) {
            $query .= " AND p.age = ?";
            $types .= "s";
            $params[] = $age;
        }
        if (!empty($gender)) {
            $query .= " AND p.gender = ?";
            $types .= "s";
            $params[] = $gender;
        }
        if (!empty($breedId)) {
            $query .= " AND p.breed_id = ?";
            $types .= "i";
            $params[] = $breedId;
        }

        if (empty($params)) {
            $result = $this->db->query($query);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        $pets = [];
        while ($row = $result->fetch_assoc()) {
            $pets[] = $this->hydrate($row);
        }
        return $pets;
    }

    public function search(?string $name, ?int $breedId, ?string $status, ?string $age, ?string $gender): array {
        $query = "SELECT p.*, b.name as breed_name FROM pets p LEFT JOIN breeds b ON p.breed_id = b.id WHERE 1 = 1";
        $types = "";
        $params = [];

        if (!empty($name)) {
            $query .= " AND p.name LIKE ?";
            $types .= "s";
            $params[] = "%" . $name . "%";
        }
        if (!empty($breedId)) {
            $query .= " AND p.breed_id = ?";
            $types .= "i";
            $params[] = $breedId;
        }
        if (!empty($status)) {
            $query .= " AND p.status = ?";
            $types .= "s";
            $params[] = $status;
        }
        if (!empty($age)) {
            $query .= " AND p.age = ?";
            $types .= "s";
            $params[] = $age;
        }
        if (!empty($gender)) {
            $query .= " AND p.gender = ?";
            $types .= "s";
            $params[] = $gender;
        }

        if (empty($params)) {
            $result = $this->db->query($query);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        $pets = [];
        while ($row = $result->fetch_assoc()) {
            $pets[] = $this->hydrate($row);
        }
        return $pets;
    }

    public function save(Pet $pet): bool {
        $stmt = $this->db->prepare("INSERT INTO pets (name, breed_id, age, gender, status, description, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $name = $pet->getName();
        $breedId = $pet->getBreedId();
        $age = $pet->getAge();
        $gender = $pet->getGender();
        $status = $pet->getStatus();
        $description = $pet->getDescription();
        $image = $pet->getImage();

        $stmt->bind_param("sisssss", $name, $breedId, $age, $gender, $status, $description, $image);
        $res = $stmt->execute();
        return $res;
    }

    public function update(Pet $pet): bool {
        $stmt = $this->db->prepare("UPDATE pets SET name = ?, breed_id = ?, age = ?, gender = ?, status = ?, description = ?, image = ? WHERE id = ?");
        $name = $pet->getName();
        $breedId = $pet->getBreedId();
        $age = $pet->getAge();
        $gender = $pet->getGender();
        $status = $pet->getStatus();
        $description = $pet->getDescription();
        $image = $pet->getImage();
        $id = $pet->getId();

        $stmt->bind_param("sisssssi", $name, $breedId, $age, $gender, $status, $description, $image, $id);
        return $stmt->execute();
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE pets SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    private function hydrate(array $row): Pet {
        return new Pet(
            (int)$row['id'],
            $row['name'],
            $row['breed_id'] !== null ? (int)$row['breed_id'] : null,
            $row['age'],
            $row['gender'],
            $row['status'],
            $row['description'],
            $row['image'],
            $row['breed_name'] ?? null
        );
    }
}
