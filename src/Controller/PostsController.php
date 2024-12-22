<?php

namespace App\Controller;

use Dgudovic\Framework\Controller\AbstractController;
use Dgudovic\Framework\Http\Response;

class PostsController extends AbstractController
{
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
}