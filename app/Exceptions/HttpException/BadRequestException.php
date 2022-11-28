<?php

namespace App\Exceptions\HttpException;

use Exception;
use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BadRequestException extends BaseException
{
    public function __construct(string|int $catalogCode = '', string $message = '', ?Exception $previous = null, array $headers = [])
    {
        parent::__construct(HttpResponse::HTTP_BAD_REQUEST, $catalogCode, $message, $previous, $headers);
    }
}
