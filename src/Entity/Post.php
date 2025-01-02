<?php

namespace App\Entity;

use DateTimeImmutable;

class Post
{
    public function __construct(
        private ?int   $id,
        private string $title,
        private string $body,
        private ?DateTimeImmutable $createdAt
    )
    {
        if (null === $this->createdAt) {
            $this->createdAt = new DateTimeImmutable();
        }
    }

    public static function create(
        string $title,
        string $body,
        ?int $id = null,
        ?DateTimeImmutable $createdAt = null
    ): Post
    {
        return new self(
            $id,
            $title,
            $body,
            $createdAt
        );
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }


}