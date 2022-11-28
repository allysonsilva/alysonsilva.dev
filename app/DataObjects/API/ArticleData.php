<?php

namespace App\DataObjects\API;

use App\Models\Tag;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Support\DTO\BaseData;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Optional;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\File;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithoutValidation;

#[MapName(SnakeCaseMapper::class)]
class ArticleData extends BaseData
{
    public function __construct(
        public readonly string $categoryId,
        public readonly string $title,
        public readonly ?string $slug,
        public readonly ?UploadedFile $image,
        public readonly string|Optional $color,
        public readonly string $content,
        public readonly string $summary,
        #[WithoutValidation]
        #[DataCollectionOf(TagData::class)]
        public readonly DataCollection $tags,
    ) {
        //
    }

    public static function fromRequest(Request $request): static
    {
        return static::from([
            ...static::$validated,
            'tags' => TagData::collection(Tag::whereKey($request->collect('tags_id'))->get()),
        ]);
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
        $uniqueColumnsRule = Rule::unique(Article::class)->ignore(request('articleUuid'));

        return [
            'category_id' => ['required', 'uuid', Rule::exists(Category::class, 'id')],
            'title' => ['required', 'min:10', $uniqueColumnsRule],
            'slug' => ['sometimes', 'required', 'min:10', 'max:255', $uniqueColumnsRule],
            'image' => [
                'sometimes',
                'required',
                File::image()
                    ->max(2 * 1024)
                    ->dimensions(Rule::dimensions()->maxWidth(1920)->maxHeight(850)),
            ],
            'content' => ['required'],
            'summary' => ['required'],
            'color' => ['sometimes', 'required', 'regex:/^#([[:xdigit:]]{3}){1,2}\b$/'],
            'tags_id' => ['required', 'array'],
            'tags_id.*' => ['required', 'uuid', Rule::exists(Tag::class, 'id')],
        ];
    }
}
