<?php

namespace App\Support\DTO;

use JsonSerializable;
use Spatie\LaravelData\Data;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;

abstract class BaseData extends Data implements Arrayable, Jsonable, JsonSerializable, Responsable
{
    protected static Arrayable | array $validated;

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toResponse($request)
    {
        return response()->json(['data' => $this->toArray()]);
    }

    public static function validate(Arrayable | array $payload): Arrayable | array
    {
        return static::$validated = parent::validate($payload);
    }

    public function validated(): Arrayable | array
    {
        return static::$validated;
    }

    /**
     * Returns only the fields that were/are filled with some information.
     * Returns only fields that are filled in, other than null.
     *
     * @return array<mixed>
     */
    public function onlyFilled(): array
    {
        return array_filter($this->toArray(), fn ($value) => ! is_null($value));
    }
}
