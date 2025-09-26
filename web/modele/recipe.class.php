<?php
class Recipe implements JsonSerializable
{
    private ?int $id;                   // Identifiant de la recette
    private string $title;              // Titre de la recette (colonne "title")
    private ?string $description;       // Description de la recette (colonne "description")
    private ?int $difficulty;           // Niveau de difficulté (facile 1,2,3 difficile) de la recette (colonne "difficulty")
    private ?int $prepTime;             // Temps de préparation de la recette en minutes (colonne "prepTime")
    private ?int $id_creator;           // ID de l'utilisateur créateur de la recette (colonne "id_creator")
    private ?string $imagePath;         // Chemin du fichier image (si utilisé)
    private ?string $imageAndroid;      // Données binaires de l'image

    public function __construct(
        ?int $id,
        string $title,
        ?string $description,
        ?int $difficulty,
        ?int $prepTime,
        ?int $id_creator,
        ?string $imagePath = null,
        ?string $imageAndroid = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->difficulty = $difficulty;
        $this->prepTime = $prepTime;
        $this->id_creator = $id_creator;
        $this->imagePath = $imagePath;
        $this->imageAndroid = $imageAndroid;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getDescription(): ?string { return $this->description; }
    public function getDifficulty(): ?int { return $this->difficulty; }
    public function getPrepTime(): ?int { return $this->prepTime; }
    public function getIdCreator(): ?int { return $this->id_creator; }
    public function getImagePath(): ?string { return $this->imagePath; }
    public function getImageAndroid(): ?string { return $this->imageAndroid; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setTitle(string $title): void { $this->title = $title; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setDifficulty(?int $difficulty): void { $this->difficulty = $difficulty; }
    public function setPrepTime(?int $prepTime): void { $this->prepTime = $prepTime; }
    public function setIdCreator(?int $id_creator): void { $this->id_creator = $id_creator; }
    public function setImagePath(?string $imagePath): void { $this->imagePath = $imagePath; }
    public function setImageAndroid(?string $imageAndroid): void { $this->imageAndroid = $imageAndroid; }

    // Méthode pour afficher l'objet sous forme de chaîne
    public function __toString(): string
    {
        return sprintf(
            "[Recipe #%d] %s (Difficulty: %d, Prep time: %d minutes)\nDescription: %s\nImage Path: %s",
            $this->id ?? 0,
            $this->title,
            $this->difficulty ?? 0,
            $this->prepTime ?? 0,
            $this->description ?? "No description",
            $this->imagePath ?? "No image"
        );
    }

    // Méthode pour la sérialisation en JSON
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'difficulty' => $this->difficulty,
            'prepTime' => $this->prepTime,
            'id_creator' => $this->id_creator,
            'imagePath' => $this->imagePath, // Inclure l'image dans la sérialisation JSON
            'imageAndroid' => $this->imageAndroid ? base64_encode($this->imageAndroid) : null,
        ];
    }
}
?>
