<?php

namespace PetMatch\Repository;

use PetMatch\Database;
use PetMatch\Domain\User;

class UserRepository {
    private \mysqli $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT id, name, email, password, phone FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User((int)$row['id'], $row['name'], $row['email'], $row['password'], $row['phone'], false);
        }
        return null;
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT id, name, email, password, phone FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User((int)$row['id'], $row['name'], $row['email'], $row['password'], $row['phone'], false);
        }
        return null;
    }

    public function findByPhone(string $phone): ?User {
        $stmt = $this->db->prepare("SELECT id, name, email, password, phone FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User((int)$row['id'], $row['name'], $row['email'], $row['password'], $row['phone'], false);
        }
        return null;
    }

    public function save(User $user): bool {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)");
        $name = $user->getName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $phone = $user->getPhone();
        $stmt->bind_param("ssss", $name, $email, $password, $phone);
        return $stmt->execute();
    }

    public function updateDetails(int $id, string $name, string $email, string $phone): bool {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        return $stmt->execute();
    }

    public function updatePassword(int $id, string $hashedPassword): bool {
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $id);
        return $stmt->execute();
    }

    public function findAdminByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT id, username, email, password FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return new User((int)$row['id'], $row['username'], $row['email'], $row['password'], null, true);
        }
        return null;
    }
}
