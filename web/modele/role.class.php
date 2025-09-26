<?php
class Role implements JsonSerializable
{
    private int $roleCode;  // Correspond à "roleCode" dans la table Role
    private string $roleName; // Correspond à "roleName" dans la table Role

    // Constructeur
    public function __construct(int $roleCode, string $roleName)
    {
        $this->roleCode = $roleCode;
        $this->roleName = $roleName;
    }

    // Getters et Setters pour roleCode
    public function getRoleCode(): int
    {
        return $this->roleCode;
    }

    public function setRoleCode(int $roleCode): void
    {
        $this->roleCode = $roleCode;
    }

    // Getters et Setters pour roleName
    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function setRoleName(string $roleName): void
    {
        $this->roleName = $roleName;
    }

    // Méthode __toString()
    public function __toString(): string
    {
        return sprintf("[Role #%d] %s", $this->roleCode, $this->roleName);
    }

    // Implémentation de JsonSerializable
    public function jsonSerialize(): array
    {
        return [
            'roleCode' => $this->roleCode,
            'roleName' => $this->roleName
        ];
    }
}
?>
