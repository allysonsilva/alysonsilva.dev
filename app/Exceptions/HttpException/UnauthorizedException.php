<?php

namespace App\Exceptions\HttpException;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UnauthorizedException extends BaseException
{
    public function __construct(string|int $catalogCode, string $message = '', array $headers = [])
    {
        parent::__construct(HttpResponse::HTTP_UNAUTHORIZED, $catalogCode, $message, null, $headers);
    }
}
