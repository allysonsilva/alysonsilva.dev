<?php

namespace App\Jobs\Traits;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;
use Spatie\YamlFrontMatter\YamlFrontMatter;

trait UpdateArticlesFromGitHub
{
    public function updateFromFiles(iterable $files): void
    {
        foreach ($files as $file) {
            $fileURL = $file['raw_url'];

            // Files other than markdown should not be processed
            if (! in_array(pathinfo($fileURL, PATHINFO_EXTENSION), ['md', 'MD'])) {
                continue;
            }

            $fileObject = YamlFrontMatter::parse(file_get_contents($fileURL));

            if ($articleId = $fileObject->id) {
                $article = Article::findOrFail($articleId);
                $article->fill(
                    array_merge($fileObject->matter(), ['content' => trim($fileObject->body())])
                );
                $article->save();
            }
        }

        Cache::forget('latest-articles');
    }
}
