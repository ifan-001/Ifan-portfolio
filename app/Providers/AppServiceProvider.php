<?php

namespace App\Providers;

use App\Models;
use App\Services\PortfolioDataService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Keep the cached public portfolio payload fresh after any CMS change.
        foreach ([
            Models\Profile::class, Models\FocusArea::class, Models\Skill::class, Models\Experience::class,
            Models\Project::class, Models\Education::class, Models\ResearchItem::class,
            Models\Achievement::class, Models\SocialLink::class, Models\SiteSetting::class, Models\BlogPost::class,
        ] as $model) {
            $model::saved(fn () => PortfolioDataService::flush());
            $model::deleted(fn () => PortfolioDataService::flush());
        }

        View::composer(['admin.*', 'portfolio.*'], function ($view) {
            $view->with('appInfo', config('portfolio.app'));
        });
    }
}
