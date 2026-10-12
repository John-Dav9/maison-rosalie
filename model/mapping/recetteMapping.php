<?php

namespace model\mapping;

use Exception;
use model\AbstractMapping;
use model\StringTrait;

class RecetteMapping extends AbstractMapping
{
    protected ?int $recette_id = null;
    // Slug permanent lu depuis la base et utilisé pour les URL publiques.
    protected ?string $recette_slug = null;
    protected ?string $recette_title = null;
    protected ?string $recette_description = null;
    protected ?string $recette_image = null;
    protected ?string $recette_duration_prep = null;
    protected ?string $recette_duration_cook = null;
    protected ?string $recette_difficulty = null;
    protected ?string $recette_date_create = null;
    protected ?int $recette_user_id = null;

    protected ?array $comments = null;
    protected ?array $categories = null;
    protected ?UserMapping $user = null;

    use StringTrait;

    public function getRecetteId(): ?int
    {
        return $this->recette_id;
    }

    public function setRecetteId(?int $recette_id): void
    {
        if ($recette_id !== null && $recette_id <= 0) {
            throw new Exception("recette_id doit être un entier positif");
        }
        $this->recette_id = $recette_id;
    }

    public function getRecetteSlug(): ?string
    {
        return $this->recette_slug;
    }

    public function setRecetteSlug(?string $recette_slug): void
    {
        if ($recette_slug !== null
            && ($recette_slug === '' || strlen($recette_slug) > 150
                || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $recette_slug))) {
            throw new Exception("Le slug de la recette n'est pas valide");
        }
        $this->recette_slug = $recette_slug;
    }

    public function getRecetteTitle(): ?string
    {
        return $this->recette_title;
    }

    public function setRecetteTitle(?string $recette_title): void
    {
        if ($recette_title === null) {
            $this->recette_title = null;
            return;
        }

        $recette_title = trim(strip_tags($recette_title));
        if (strlen($recette_title) < 3 || strlen($recette_title) > 100) {
            throw new Exception("Le titre de la recette doit faire entre 3 et 100 caractères");
        }
        $this->recette_title = $recette_title;
    }


    public function getRecetteDescription(): ?string
    {
        return $this->recette_description;
    }

    public function setRecetteDescription(?string $recette_description): void
    {
        if ($recette_description === null || trim($recette_description) === '') {
            throw new Exception("La description de la recette est obligatoire");
        }

        $this->recette_description = trim($recette_description);
    }

    public function getRecetteImage(): ?string
    {
        return $this->recette_image;
    }

    public function setRecetteImage(?string $recette_image): void
    {
        $this->recette_image = $recette_image;
    }

    public function getRecetteDurationPrep(): ?string
    {
        return $this->recette_duration_prep;
    }

    public function setRecetteDurationPrep(?string $recette_duration_prep): void
    {
        $this->recette_duration_prep = $recette_duration_prep;
    }

    public function getRecetteDurationCook(): ?string
    {
        return $this->recette_duration_cook;
    }

    public function setRecetteDurationCook(?string $recette_duration_cook): void
    {
        $this->recette_duration_cook = $recette_duration_cook;
    }

    public function getRecetteDifficulty(): ?string
    {
        return $this->recette_difficulty;
    }

    public function setRecetteDifficulty(?string $recette_difficulty): void
    {
        $this->recette_difficulty = $recette_difficulty;
    }

    public function getRecetteDateCreate(): ?string
    {
        return $this->recette_date_create;
    }

    public function setRecetteDateCreate(?string $recette_date_create): void
    {
        if ($recette_date_create === null) {
            $this->recette_date_create = null;
            return;
        }

        $timestamp = strtotime($recette_date_create);
        if ($timestamp === false) {
            throw new Exception("La date de création de la recette n'est pas valide");
        }
        $this->recette_date_create = date('Y-m-d H:i:s', $timestamp);
    }

    public function getRecetteUserId(): ?int
    {
        return $this->recette_user_id;
    }

    public function setRecetteUserId(?int $recette_user_id): void
    {
        if ($recette_user_id !== null && $recette_user_id <= 0) {
            throw new Exception("recette_user_id doit être un entier positif");
        }
        $this->recette_user_id = $recette_user_id;
    }

    public function getComments(): ?array
    {
        return $this->comments;
    }

    public function setComments(?array $comments): void
    {
        $this->comments = $comments;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): void
    {
        $this->categories = $categories;
    }

    public function getUser(): ?UserMapping
    {
        return $this->user;
    }

    public function setUser(?UserMapping $user): void
    {
        $this->user = $user;
    }
}