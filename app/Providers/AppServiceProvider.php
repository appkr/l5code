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
            // 사용자가 제출한 암호화된 쿠키를 복호화한 후
            // 애플리케이션 전체에 적용할 언어를 설정한다.
            app()->setLocale(\Crypt::decrypt($locale));
        }

        // 카본 인스턴스의 언어를 설정한다.
        \Carbon\Carbon::setLocale(app()->getLocale());

        view()->composer('*', function ($view) {
            $allTags = \Cache::rememberForever('tags.list', function () {
                return \App\Tag::all();
            });

            $currentUser = auth()->user();
            $currentRouteName = \Route::currentRouteName();
            $currentLocale = app()->getLocale();
            $currentUrl = current_url();

            $view->with(compact('allTags', 'currentUser', 'currentRouteName', 'currentLocale', 'currentUrl'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        if ($this->app->environment('local')) {
//            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
//        }

        $this->app->singleton('optimus', function () {
            return new \Jenssegers\Optimus\Optimus(120911477, 1136962013, 320641982);
        });
    }
}
