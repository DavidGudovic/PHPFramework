<?php

namespace App\Controller;

use Dgudovic\Framework\Http\Response;

class HomeController
{
    public function index(): Response
    {
        return new Response('<h1>Hello, World!</h1>');
    }
}