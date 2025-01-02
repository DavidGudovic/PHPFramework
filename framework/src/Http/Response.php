<?php

namespace Dgudovic\Framework\Http;

use JetBrains\PhpStorm\NoReturn;

class Response
{
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_REDIRECT = 302;
    public const HTTP_OK = 200;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;

    public function __construct(
        public ?string $content = '',
        public int     $status = 200,
        public array   $headers = [],
    )
    {
        http_response_code($this->status);
    }

    #[NoReturn] public function send(): void
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getHeader(string $header): mixed
    {
        return $this->headers[$header] ?? null;
    }
}