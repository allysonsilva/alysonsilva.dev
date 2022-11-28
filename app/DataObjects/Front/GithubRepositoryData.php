<?php

namespace App\DataObjects\Front;

use App\Support\DTO\BaseData;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class GithubRepositoryData extends BaseData
{
    public function __construct(
        public readonly string $name,
        public readonly string $fullName,
        #[MapInputName('html_url')]
        public readonly string $url,
        public readonly string $description,
        public readonly string $language,
        public readonly int $stargazersCount,
        public readonly int $forksCount,
    ) {
        //
    }
}
