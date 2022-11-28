<?php

namespace App\Http\Controllers;

use Spatie\Feed\Feed;
use Spatie\Feed\Helpers\ResolveFeedItems;

class FeedController
{
    public function __invoke()
    {
        $feeds = config('feed.feeds');

        $name = 'main';

        $feed = $feeds[$name] ?? null;

        abort_unless($feed, 404);

        $items = ResolveFeedItems::resolve($name, $feed['items']);

        return new Feed(
            $feed['title'],
            $items,
            url('/feed'),
            $feed['view'] ?? 'feed::atom',
            $feed['description'] ?? '',
            $feed['language'] ?? 'en-US',
            $feed['image'] ?? '',
            $feed['format'] ?? 'atom',
        );
    }
}
