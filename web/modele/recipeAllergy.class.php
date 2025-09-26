<?php
class RecipeAllergy implements JsonSerializable
{
    private int $recipeId;   // L'ID de la recette
    private int $allergyId;  // L'ID de l'allergie

    // Constructeur
    public function __construct(int $recipeId, int $allergyId)
    {
        $this->recipeId = $recipeId;
        $this->allergyId = $allergyId;
    }

    // Getters
    public function getRecipeId(): int
    {
        return $this->recipeId;
    }

    public function getAllergyId(): int
    {
        return $this->allergyId;
    }

    // Setters
    public function setRecipeId(int $recipeId): void
    {
        $this->recipeId = $recipeId;
    }

    public function setAllergyId(int $allergyId): void
    {
        $this->allergyId = $allergyId;
    }

    // Méthode pour afficher l'objet sous forme de chaîne
    public function __toString(): string
    {
        return sprintf(
            "[RecipeAllergy] Recipe ID: %d, Allergy ID: %d",
            $this->recipeId,
            $this->allergyId
        );
    }

    // Méthode pour la sérialisation en JSON
    public function jsonSerialize(): array
    {
        return [
            'recipeId' => $this->recipeId,
            'allergyId' => $this->allergyId,
        ];
    }
}
?>
