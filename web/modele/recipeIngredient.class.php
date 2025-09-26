<?php
class RecipeIngredient implements JsonSerializable
{
    private int $recipeId;        // L'ID de la recette
    private int $ingredientId;    // L'ID de l'ingrédient
    private int $quantity;        // Quantité de l'ingrédient dans la recette
    private ?string $unitOfMeasure; // Unité de mesure de l'ingrédient (ex : grammes, ml, unités)

    // Constructeur
    public function __construct(int $recipeId, int $ingredientId, int $quantity, ?string $unitOfMeasure = null)
    {
        $this->recipeId = $recipeId;
        $this->ingredientId = $ingredientId;
        $this->quantity = $quantity;
        $this->unitOfMeasure = $unitOfMeasure;
    }

    // Getters
    public function getRecipeId(): int
    {
        return $this->recipeId;
    }

    public function getIngredientId(): int
    {
        return $this->ingredientId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getUnitOfMeasure(): ?string
    {
        return $this->unitOfMeasure;
    }

    // Setters
    public function setRecipeId(int $recipeId): void
    {
        $this->recipeId = $recipeId;
    }

    public function setIngredientId(int $ingredientId): void
    {
        $this->ingredientId = $ingredientId;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setUnitOfMeasure(?string $unitOfMeasure): void
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }

    // Méthode pour afficher l'objet sous forme de chaîne
    public function __toString(): string
    {
        return sprintf(
            "[RecipeIngredient] Recipe ID: %d, Ingredient ID: %d, Quantity: %d, Unit: %s",
            $this->recipeId,
            $this->ingredientId,
            $this->quantity,
            $this->unitOfMeasure ?? 'N/A'
        );
    }

    // Méthode pour la sérialisation en JSON
    public function jsonSerialize(): array
    {
        return [
            'recipeId' => $this->recipeId,
            'ingredientId' => $this->ingredientId,
            'quantity' => $this->quantity,
            'unitOfMeasure' => $this->unitOfMeasure,
        ];
    }
}
?>
