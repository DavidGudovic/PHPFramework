<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostMapper;
use App\Repository\PostRepository;
use DateMalformedStringException;
use Dgudovic\Framework\Controller\AbstractController;
use Dgudovic\Framework\Http\NotFoundException;
use Dgudovic\Framework\Http\RedirectResponse;
use Dgudovic\Framework\Http\Response;
use Doctrine\DBAL\Exception;

class PostsController extends AbstractController
{
    public function __construct(
        private readonly PostMapper     $postMapper,
        private readonly PostRepository $postRepository
    )
    {
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception|NotFoundException
     */
    public function show(int $id): Response
    {
        $post = $this->postRepository->findOrFail($id);

        return $this->render('posts/show.html.twig', [
            'post' => $post
        ]);
    }

    public function create(): Response
    {
        return $this->render('posts/create.html.twig');
    }

    public function store(): RedirectResponse
    {
        $title = $this->request->postParams['title'];
        $body = $this->request->postParams['body'];

        $post = Post::create($title, $body);

        $this->postMapper->save($post);

        return new RedirectResponse('/posts');
    }
}