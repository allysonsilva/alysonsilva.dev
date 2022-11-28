<?php

namespace App\DataObjects\API;

use App\Models\Tag;
use App\Support\DTO\BaseData;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Optional;

class TagData extends BaseData
{
    public function __construct(
        public readonly string | null $id,
        public readonly string $title,
        public readonly string | Optional $slug,
    ) {
        //
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public static function rules(array $payload): array
    {
        $uniqueColumnsRule = Rule::unique(Tag::class)->ignore(request('tagUuid'));

        return [
            'title' => ['required', $uniqueColumnsRule],
            'slug' => ['sometimes', 'required', 'min:10', 'max:255', $uniqueColumnsRule],
        ];
    }
}
