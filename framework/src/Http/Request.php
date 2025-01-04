<?php

namespace Dgudovic\Framework\Http;

use Dgudovic\Framework\Session\SessionInterface;

readonly class Request
{
    private SessionInterface $session;
    public function __construct(
        public array $getParams,
        public array $postParams,
        public array $cookies,
        public array $files,
        public array $server,
    )
    {
    }

    public static function createFromGlobals(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER,
        );
    }

    public function getPathInfo(): string
    {
        return parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }
}