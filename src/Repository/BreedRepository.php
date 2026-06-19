<?php

namespace PetMatch\Repository;

use PetMatch\Database;

class BreedRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        $result = $this->db->query("SELECT * FROM breeds ORDER BY name ASC");
        $breeds = [];
        while ($row = $result->fetch_assoc()) {
            $breeds[] = $row;
        }
        return $breeds;
    }

    public function search(?string $name): array {
        $query = "SELECT * FROM breeds";
        if (!empty($name)) {
            $stmt = $this->db->prepare("SELECT * FROM breeds WHERE name LIKE ? ORDER BY name ASC");
            $likeName = "%" . $name . "%";
            $stmt->bind_param("s", $likeName);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query("SELECT * FROM breeds ORDER BY name ASC");
        }
        $breeds = [];
        while ($row = $result->fetch_assoc()) {
            $breeds[] = $row;
        }
        return $breeds;
    }

    public function findByName(string $name): ?array {
        $stmt = $this->db->prepare("SELECT * FROM breeds WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM breeds WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    public function create(string $name): bool {
        $stmt = $this->db->prepare("INSERT INTO breeds (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }

    public function update(int $id, string $name): bool {
        $stmt = $this->db->prepare("UPDATE breeds SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM breeds WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function hasAssociatedPets(int $id): bool {
        $stmt = $this->db->prepare("SELECT id FROM pets WHERE breed_id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
}
