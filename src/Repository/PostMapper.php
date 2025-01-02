<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

readonly class PostMapper
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function save(Post $post): void
    {
        $stmt = $this->connection->prepare("
        INSERT INTO posts(title, body, created_at)
        VALUES (:title, :body, :created_at)
        ");

        $stmt->executeStatement([
            ':title' => $post->getTitle(),
            ':body' => $post->getBody(),
            ':created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);

        $id = $this->connection->lastInsertId();

        $post->setId($id);
    }
}