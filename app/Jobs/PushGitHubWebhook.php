<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Traits\UpdateArticlesFromGitHub;
use Illuminate\Support\Arr;
use Spatie\GitHubWebhooks\Models\GitHubWebhookCall;

class PushGitHubWebhook implements ShouldQueue
{
    use UpdateArticlesFromGitHub, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const UPDATE_BLOG = '#update-blog';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public GitHubWebhookCall $webhookCall)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        /** @var array<mixed> */
        $payload = $this->webhookCall->payload();

        // Isso garante que foi um push em uma branch, e não uma tag (refs/tags/).
        $isBranch = str_starts_with($payload['ref'], 'refs/heads/');
        // Se tiver itens, significa que foi um push com commits.
        $hasCommits = ! empty($payload['commits']);
        // Se for true, quer dizer que foi uma exclusão de branch ou tag, e não um push de código.
        $notDeleted = $payload['deleted'] === false;

        // É um push de commits pra uma branch?
        $isPushCommit = $isBranch && $hasCommits && $notDeleted;

        if (! $isPushCommit) {
            return;
        }

        foreach ($payload['commits'] as $commit) {
            if (str_contains(strtolower($commit['message']), self::UPDATE_BLOG)) {
                $commitFiles = Http::withOptions(['connect_timeout' => 5, 'timeout' => 10])
                                    ->acceptJson()
                                    ->baseUrl(data_get($payload, 'repository.url'))
                                    ->throw()
                                    ->get('/commits/' . $commit['id']);

                $this->updateFromFiles(Arr::wrap($commitFiles->json('files')));

                break;
            }
        }
    }
}
