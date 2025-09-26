<?php

class User implements JsonSerializable
{
    private ?int $id;                // Correspond à "id" dans la table User
    private string $name;            // Correspond à "name" dans la table User
    private string $email;           // Correspond à "email" dans la table User
    private string $password;        // Correspond à "password" dans la table User
    private ?int $role;              // Correspond à "role" dans la table User (1 = Admin, 2 = Utilisateur, 3 = Visiteur)

    // Constructor
    public function __construct(
        ?int $id,
        string $name,
        string $email,
        string $password,
        ?int $role
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // Getters and Setters for $id
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    // Getters and Setters for $name
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    // Getters and Setters for $email
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    // Getters and Setters for $password
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    // Getters and Setters for $role
    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(?int $role): void
    {
        $this->role = $role;
    }

    // Method to verify password
    public function verifyPassword(string $password): bool
    {
        // Assuming $this->password is a hashed password (e.g., bcrypt)
        return password_verify($password, $this->password);
    }

    // Method to hash the password
    public function hashPassword(): void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // __toString method
    public function __toString(): string
    {
        return sprintf(
            "[User #%d] %s - %s (%s)",
            $this->id,
            $this->name,
            $this->email,
            $this->role ? $this->role : 3 // Default to "User" if no role is set
        );
    }

    // Serialize the object to JSON
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            // Do not include password for security reasons
            'role' => $this->role ? $this->role : 3 // Default to "User" if no role is set
        ];
    }
}
?>
