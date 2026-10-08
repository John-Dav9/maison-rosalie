<?php

namespace model\mapping;

use model\AbstractMapping;

class NoteMapping extends AbstractMapping
{
    protected ?int $id = null;
    protected ?int $note = null;
    protected ?string $created_at = null;
    protected ?int $users_id = null;
    protected ?int $recipe_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): void
    {
        $this->note = $note;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUsersId(): ?int
    {
        return $this->users_id;
    }

    public function setUsersId(?int $users_id): void
    {
        $this->users_id = $users_id;
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
