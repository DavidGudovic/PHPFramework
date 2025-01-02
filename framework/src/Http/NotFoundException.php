<?php

namespace Dgudovic\Framework\Http;

use Exception;

class NotFoundException extends HttpException
{
    private int $statusCode = 404;
}