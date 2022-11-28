<?php

namespace App\Providers;

use Illuminate\View\View;
use Illuminate\View\Factory;
use App\View\Shared\HomeSeo;
use Illuminate\Support\Facades;
use App\View\Components\MenuCategories;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(MenuCategories::class, MenuCategories::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Factory $factory): void
    {
        $this->loadViewsFrom(resource_path('views/pages'), 'pages');
        $this->loadViewsFrom(public_path('/'), 'public');

        Facades\View::share('author', 'Alyson Silva');
        Facades\View::share('me', 'Alyson Silva - Software Engineer');

        Facades\View::composer(['pages::home'], function (View $view) {
            $view->with((new HomeSeo)());
        });

        $factory->addExtension('xml', 'file');
        $factory->addExtension('json', 'blade');
    }
}
