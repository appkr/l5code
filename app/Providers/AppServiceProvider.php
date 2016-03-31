<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (is_api_domain() and request()->getLanguages()) {
            $preferred = request()->getPreferredLanguage();
            $locale = str_contains($preferred, 'ko') ? 'ko' : 'en';
            app()->setLocale($locale);
        }

        if ($locale = request()->cookie('locale__myapp')) {
            app()->setLocale(\Crypt::decrypt($locale));
        }

        \Carbon\Carbon::setLocale(app()->getLocale());

        view()->composer('*', function($view) {
            $allTags = \Cache::rememberForever('tags.list', function() {
                return \App\Tag::all();
            });
            $currentUser = auth()->user();
            $sortCols = config('project.sorting');
            $currentLocale = app()->getLocale();
            $currentUrl = current_url();

            $view->with(compact('allTags', 'currentUser', 'sortCols', 'currentLocale', 'currentUrl'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
