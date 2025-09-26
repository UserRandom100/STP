<?php
class Allergy implements JsonSerializable
{
    private int $id;
    private bool $nuts;
    private bool $gluten;
    private bool $dairy;
    private bool $seafood;

    public function __construct(int $id, ?bool $nuts, ?bool $gluten, ?bool $dairy, ?bool $seafood)
    {
        $this->id = $id;
        // Remplacer les valeurs null par false
        $this->nuts = $nuts ?? false;
        $this->gluten = $gluten ?? false;
        $this->dairy = $dairy ?? false;
        $this->seafood = $seafood ?? false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function hasNuts(): bool
    {
        return $this->nuts;
    }

    public function setNuts(bool $nuts): void
    {
        $this->nuts = $nuts;
    }

    public function hasGluten(): bool
    {
        return $this->gluten;
    }

    public function setGluten(bool $gluten): void
    {
        $this->gluten = $gluten;
    }

    public function hasDairy(): bool
    {
        return $this->dairy;
    }

    public function setDairy(bool $dairy): void
    {
        $this->dairy = $dairy;
    }

    public function hasSeafood(): bool
    {
        return $this->seafood;
    }

    public function setSeafood(bool $seafood): void
    {
        $this->seafood = $seafood;
    }

    public function __toString(): string
    {
        return sprintf("[Allergy #%d] Nuts: %s, Gluten: %s, Dairy: %s, Seafood: %s",
            $this->id,
            $this->nuts ? "Yes" : "No",
            $this->gluten ? "Yes" : "No",
            $this->dairy ? "Yes" : "No",
            $this->seafood ? "Yes" : "No"
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nuts' => $this->nuts,
            'gluten' => $this->gluten,
            'dairy' => $this->dairy,
            'seafood' => $this->seafood
        ];
    }
}
?>
