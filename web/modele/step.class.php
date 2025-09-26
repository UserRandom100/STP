<?php
class Step implements JsonSerializable
{
    private int $recipeId;          // Identifiant de la recette associée (clé étrangère vers la table Recipe)
    private int $stepNumber;        // Numéro de l'étape dans la recette (colonne "stepNumber")
    private string $stepDescription; // Description de l'étape (colonne "stepDescription")

    // Constructeur
    public function __construct(int $recipeId, int $stepNumber, string $stepDescription)
    {
        $this->recipeId = $recipeId;
        $this->stepNumber = $stepNumber;
        $this->stepDescription = $stepDescription;
    }

    // Getters
    public function getRecipeId(): int
    {
        return $this->recipeId;
    }

    public function getStepNumber(): int
    {
        return $this->stepNumber;
    }

    public function getStepDescription(): string
    {
        return $this->stepDescription;
    }

    // Setters
    public function setRecipeId(int $recipeId): void
    {
        $this->recipeId = $recipeId;
    }

    public function setStepNumber(int $stepNumber): void
    {
        $this->stepNumber = $stepNumber;
    }

    public function setStepDescription(string $stepDescription): void
    {
        $this->stepDescription = $stepDescription;
    }

    // Méthode pour afficher l'objet sous forme de chaîne
    public function __toString(): string
    {
        return sprintf(
            "[Step #%d for Recipe #%d] %s",
            $this->stepNumber,
            $this->recipeId,
            $this->stepDescription
        );
    }

    // Méthode pour la sérialisation en JSON
    public function jsonSerialize(): array
    {
        return [
            'recipeId' => $this->recipeId,
            'stepNumber' => $this->stepNumber,
            'stepDescription' => $this->stepDescription,
        ];
    }
}
?>
