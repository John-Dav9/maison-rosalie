<?php

namespace model\mapping;

use model\AbstractMapping;

class StepsMapping extends AbstractMapping
{
    protected ?int $id = null;
    protected ?int $recette_id = null;
    protected ?int $nb_step = null;
    protected ?string $title = null;
    protected ?string $description = null;
    protected ?string $image = null;
    protected ?int $recipe_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getRecetteId(): ?int
    {
        return $this->recette_id;
    }

    public function setRecetteId(?int $recette_id): void
    {
        $this->recette_id = $recette_id;
    }

    public function getNbStep(): ?int
    {
        return $this->nb_step;
    }

    public function setNbStep(?int $nb_step): void
    {
        $this->nb_step = $nb_step;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getRecipeId(): ?int
    {
        return $this->recipe_id;
    }

    public function setRecipeId(?int $recipe_id): void
    {
        $this->recipe_id = $recipe_id;
    }
}
