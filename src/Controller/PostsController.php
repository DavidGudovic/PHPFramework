<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostMapper;
use Dgudovic\Framework\Controller\AbstractController;
use Dgudovic\Framework\Http\Response;
use Doctrine\DBAL\Exception;

class PostsController extends AbstractController
{
    public function __construct(private readonly PostMapper $postMapper)
    {
    }

    public function show(int $id): Response
    {
        return $this->render('posts/show.html.twig', [
            'postID' => $id
        ]);
    }

    public function create(): Response
    {
        return $this->render('posts/create.html.twig');
    }

    public function store(): void
    {
        $title = $this->request->postParams['title'];
        $body = $this->request->postParams['body'];

        $post = Post::create($title, $body);

        try {
            $this->postMapper->save($post);
        } catch (Exception $e) {
            dd($e);
        }

        dd($post);
    }
}