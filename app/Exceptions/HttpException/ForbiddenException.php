<?php

namespace App\Exceptions\HttpException;

use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ForbiddenException extends BaseException
{
    public function __construct(string|int $catalogCode, string $message = '', array $headers = [])
    {
        if (empty($message)) {
            $message = __("you don't have permissions to perform this request.");
        }

        parent::__construct(HttpResponse::HTTP_FORBIDDEN, $catalogCode, $message, null, $headers);
    }
}
