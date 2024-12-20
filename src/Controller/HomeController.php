<?php

namespace App\Controller;

use Dgudovic\Framework\Controller\AbstractController;
use Dgudovic\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function index(): Response
    {
        dd($this->container?->get('twig'));

        return new Response('<h1>Hello, World!</h1>');
    }
}