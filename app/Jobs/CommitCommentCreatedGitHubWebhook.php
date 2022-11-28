<?php

namespace App\Jobs;

use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Traits\UpdateArticlesFromGitHub;
use Spatie\GitHubWebhooks\Models\GitHubWebhookCall;

class CommitCommentCreatedGitHubWebhook implements ShouldQueue
{
    use UpdateArticlesFromGitHub, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const COMMENT_BODY = '#update-blog';

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
    public function handle()
    {
        /** @var array<mixed> */
        $payload = $this->webhookCall->payload();

        $containsCommentUpdateBlog = str_contains(data_get($payload, 'comment.body'), self::COMMENT_BODY);

        if (! $containsCommentUpdateBlog) {
            return;
        }

        // request example => https://api.github.com/repos/allysonsilva/blog-posts/commits/41ef1b5478378ddab52910d56b41cc3fbe1a0340
        // @see https://docs.github.com/pt/rest/commits/commits?apiVersion=2022-11-28#get-a-commit
        $commitFiles = Http::acceptJson()->get(data_get($payload, 'repository.url') . '/commits/' . data_get($payload, 'comment.commit_id'));

        $this->updateFromFiles(Arr::wrap(data_get($commitFiles->json(), 'files')));
    }
}
