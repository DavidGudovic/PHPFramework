<?php

namespace App\Controller;

use Dgudovic\Framework\Controller\AbstractController;
use Dgudovic\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
}