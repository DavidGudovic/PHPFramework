<?php

namespace Dgudovic\Framework\Http;

use JetBrains\PhpStorm\NoReturn;

class RedirectResponse extends Response
{
   public function __construct(string $url)
   {
       parent::__construct('', parent::HTTP_REDIRECT, ['location' => $url]);
   }

   #[NoReturn]
   public function send(): void
   {
       header('Location: ' . $this->getHeader('location'), true, $this->getStatus());
       exit();
   }
}