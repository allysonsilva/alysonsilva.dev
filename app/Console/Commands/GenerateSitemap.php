<?php

namespace App\Console\Commands;

use App\Models\Article;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;
use App\View\Components\MenuCategories;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Sitemap::create()
                ->add(Url::create('/'))
                ->add(Url::create(route('web.about-me'))->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.9))
                ->add(Url::create(route('web.blog.posts'))->setPriority(0.9))
                ->add((app(MenuCategories::class))->categories)
                ->add(Article::all())
                ->add(Url::create(route('web.policies.show'))->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.6))
                // ->writeToDisk('public', 'sitemap.xml');
                ->writeToFile(public_path('sitemap.xml'));

        $this->line('');
        $this->info('Arquivo sitemap.xml criado com sucesso ✅');

        return Command::SUCCESS;
    }
}
