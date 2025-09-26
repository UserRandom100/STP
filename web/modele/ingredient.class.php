<?php
class Ingredient implements JsonSerializable
{
    private ?int $id;          // Identifiant de l'ingrédient
    private string $name;      // Nom de l'ingrédient
    private ?string $unitOfMeasure;  // Unité de mesure de l'ingrédient (ex : grammes, ml, unités)

    // Constructeur
    public function __construct(?int $id, string $name, ?string $unitOfMeasure = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->unitOfMeasure = $unitOfMeasure;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUnitOfMeasure(): ?string
    {
        return $this->unitOfMeasure;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setUnitOfMeasure(?string $unitOfMeasure): void
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }

    // Méthode pour afficher l'objet sous forme de chaîne
    public function __toString(): string
    {
        return sprintf(
            "[Ingredient #%d] %s, Unit: %s",
            $this->id ?? 0,
            $this->name,
            $this->unitOfMeasure ?? 'N/A'
        );
    }

    // Méthode pour la sérialisation en JSON
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unitOfMeasure' => $this->unitOfMeasure,
        ];
    }
}
?>
