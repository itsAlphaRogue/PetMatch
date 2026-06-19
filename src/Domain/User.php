<?php

namespace PetMatch\Domain;

class User {
    private ?int $id;
    private string $name;
    private string $email;
    private string $password;
    private ?string $phone;
    private bool $isAdmin;

    public function __construct(
        ?int $id,
        string $name,
        string $email,
        string $password,
        ?string $phone = null,
        bool $isAdmin = false
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->isAdmin = $isAdmin;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function isAdmin(): bool {
        return $this->isAdmin;
    }
}
