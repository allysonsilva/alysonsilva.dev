<?php

namespace App\Exceptions;

use Exception;
use JsonSerializable;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

abstract class BaseException extends Exception implements Jsonable, JsonSerializable, Arrayable, HttpExceptionInterface
{
    protected int $statusCode;

    protected string|int $catalogCode;

    protected array $headers = [];

    protected array $additionalDetail = [];

    public function __construct(int $statusCode, string|int $catalogCode = '', string $message = 'Server Error', ?Exception $previous = null, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->catalogCode = $catalogCode;
        $this->headers = $headers;

        parent::__construct($message, $statusCode, $previous);
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        /*
         * Blank implementation to prevent this exception from unnecessarily polluting the application log.
         * For the exception to be placed in the log, set `false` as the return type in the child classes.
         */
        // @see https://laravel.com/docs/9.x/errors#renderable-exceptions
        return false;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getCatalogCode(): string|int
    {
        return $this->catalogCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert exception to JSON.
     *
     * @param int $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        return (json_encode($this->toArray(), $options)) ?: '';
    }

    /**
     * @param array<mixed> $additionalDetail
     *
     * @example
     *      (new ForbiddenException("Message"))
     *      ->withAdditional([
     *          'foo' => 'bar',
     *      ])->toss();
     */
    public function withAdditional(array $additionalDetail): static
    {
        $this->additionalDetail = $additionalDetail;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function additional(): array
    {
        return $this->additionalDetail;
    }

    /**
     * Fails with the current exception object.
     *
     * @throws \App\Exceptions\BaseException
     */
    public function toss(): void
    {
        throw $this;
    }

    /**
     * Return the Exception as an array.
     *
     * @return array<mixed>
     */
    public function toArray(): array
    {
        $e = $this;

        if (config('app.debug')) {
            return [
                'message' => $e->getMessage(),
                'code' => $e->getCatalogCode(),
                'additional' => $e->additional(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => collect($e->getTrace())->map(fn ($trace) => Arr::except($trace, ['args']))->all(),
            ];
        }

        return [];
    }
}
