<?php

namespace App\DataObjects\API;

use App\Models\Tag;
use App\Support\DTO\BaseData;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

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
     * @param \Spatie\LaravelData\Support\Validation\ValidationContext $context
     *
     * @return array<string, mixed>
     */
    public static function rules(ValidationContext $context): array
    {
        $uniqueColumnsRule = Rule::unique(Tag::class)->ignore(request('tagUuid'));

        return [
            'title' => ['required', $uniqueColumnsRule],
            'slug' => ['sometimes', 'required', 'min:10', 'max:255', $uniqueColumnsRule],
        ];
    }
}
