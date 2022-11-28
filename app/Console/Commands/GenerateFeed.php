<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Console\Command;

class GenerateFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the RSS Feed.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // route => `/rss/{sha1(Str::random(10))}`
        $request = Request::create(route('web.rss.xml'), 'GET');

        /** @var \Illuminate\Http\JsonResponse */
        $response = app()->handle($request);

        file_put_contents(public_path('feed.xml'), $response->getContent());

        $this->line('');
        $this->info('Arquivo feed.xml criado com sucesso ✅');

        return Command::SUCCESS;
    }
}
