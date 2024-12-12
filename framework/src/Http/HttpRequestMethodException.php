<?php

namespace Dgudovic\Framework\Http;

class HttpRequestMethodException extends HttpException
{
    private int $statusCode = 405;
}