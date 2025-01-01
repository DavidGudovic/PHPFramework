<?php

namespace Dgudovic\Framework\Controller;

use Dgudovic\Framework\Http\Request;
use Dgudovic\Framework\Http\Response;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;
    protected Request $request;
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $template, array $parameteres = [], Response $response = null): Response
    {
        $content = $this->container->get('twig')->render($template, $parameteres);

        $response ??= new Response();

        return $response->setContent($content);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
}