<?php

namespace Dgudovic\Framework\Http;

use JetBrains\PhpStorm\NoReturn;

readonly class Response
{
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
}