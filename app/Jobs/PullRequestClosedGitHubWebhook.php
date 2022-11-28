<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Jobs\Traits\UpdateArticlesFromGitHub;
use Spatie\GitHubWebhooks\Models\GitHubWebhookCall;

class PullRequestClosedGitHubWebhook implements ShouldQueue
{
    use UpdateArticlesFromGitHub, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const UPDATE_BLOG_LABEL = 'update-blog';

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

        // Checks if the pull request was successfully merged
        $pullRequestMerged = (data_get($payload, 'pull_request.merged') === true);

        // Checks if the pull request contains a label named `self::UPDATE_BLOG_LABEL`
        $containsUpdateBlogLabel = collect(data_get($payload, 'pull_request.labels'))->contains('name', self::UPDATE_BLOG_LABEL);

        if ($pullRequestMerged && $containsUpdateBlogLabel) {
            // request example => https://api.github.com/repos/allysonsilva/blog-posts/pulls/8/files
            $pullRequestFiles = Http::acceptJson()->get(data_get($payload, 'pull_request.url') . '/' . rawurlencode('files'));

            $this->updateFromFiles($pullRequestFiles->json());
        }
    }
}
