<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            try {
                $settings = \App\Models\Settings::pluck('description', 'label')->toArray();
                $view->with('global_settings', $settings);
            } catch (\Exception $e) {
                $view->with('global_settings', []);
            }
        });
    }
}
