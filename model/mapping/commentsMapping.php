<?php

namespace model\mapping;

use model\AbstractMapping;

class CommentsMapping extends AbstractMapping
{
    protected ?int $id = null;
    protected ?string $author = null;
    protected ?string $subject = null;
    protected ?string $message = null;
    protected ?string $created_at = null;
    protected ?int $recipe_id = null;
    protected ?int $users_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getRecipeId(): ?int
    {
        return $this->recipe_id;
    }

    public function setRecipeId(?int $recipe_id): void
    {
        $this->recipe_id = $recipe_id;
    }

    public function getUsersId(): ?int
    {
        return $this->users_id;
    }

    public function setUsersId(?int $users_id): void
    {
        $this->users_id = $users_id;
    }
}
