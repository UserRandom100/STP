<?php
class UserSavedRecipe implements JsonSerializable
{
    private int $userId;       // L'ID de l'utilisateur
    private int $recipeId;     // L'ID de la recette sauvegardée

    // Constructeur
    public function __construct(int $userId, int $recipeId)
    {
        $this->userId = $userId;
        $this->recipeId = $recipeId;
    }

    // Getters
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRecipeId(): int
    {
        return $this->recipeId;
    }

    // Setters
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setRecipeId(int $recipeId): void
    {
        $this->recipeId = $recipeId;
    }

    // Méthode pour afficher l'objet sous forme de chaîne
    public function __toString(): string
    {
        return sprintf(
            "[UserSavedRecipe] User ID: %d, Recipe ID: %d",
            $this->userId,
            $this->recipeId
        );
    }

    // Méthode pour la sérialisation en JSON
    public function jsonSerialize(): array
    {
        return [
            'userId' => $this->userId,
            'recipeId' => $this->recipeId,
        ];
    }
}
?>
