<?php

namespace App\Exceptions\HttpException;

use Illuminate\Support\Str;
use App\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class NotFoundException extends BaseException
{
    public function __construct(string|int $catalogCode, string $message = '', array $headers = [])
    {
        parent::__construct(HttpResponse::HTTP_NOT_FOUND, $catalogCode, $message, null, $headers);
    }

    /**
     * Customized message if the resource ID exists or not.
     *
     * @param string $key
     * @param int|null $id
     *
     * @return string
     */
    protected function lang(string $key, ?int $id = null): string
    {
        $message = __($key, [! is_null($id) ? "with ID #$id" : '']);

        // This takes any part of the string that matches {2,}
        // (a space character occurring two or more times in a row) and replaces it with a single space.
        return Str::of($message)->replaceMatches('/ {2,}/', ' ');
    }
}
