<?php

namespace App\Repository;

use App\Entity\Post;
use DateMalformedStringException;
use DateTimeImmutable;
use Dgudovic\Framework\Http\NotFoundException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

readonly class PostRepository
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     * @throws DateMalformedStringException
     */
    public function findById(int $id): ?Post
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('id', 'title', 'body', 'created_at')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id);

        $row = $queryBuilder->executeQuery()->fetchAssociative();

        if (empty($row)) {
            return null;
        }

        return Post::create(
            title: $row['title'],
            body: $row['body'],
            id: $row['id'],
            createdAt: new DateTimeImmutable($row['created_at'])
        );
    }

    /**
     * @throws DateMalformedStringException
     * @throws NotFoundException
     * @throws Exception
     */
    public function findOrFail(int $id): Post
    {
        $post = $this->findById($id);

        if(!$post) {
            throw new NotFoundException('Post not found');
        }

        return $post;
    }
}