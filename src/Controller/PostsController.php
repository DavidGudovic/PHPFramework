<?php

namespace App\Controller;

use Dgudovic\Framework\Http\Response;

class PostsController
{
    public function show(int $id): Response
    {
        return new Response("<h1>Showing the post with id $id</h1>");
    }
}