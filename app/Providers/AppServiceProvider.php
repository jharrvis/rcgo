<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Schema::defaultStringLength(191);
        Paginator::useTailwind();

        // Share social media settings with landing page views
        try {
            // Check if table exists to avoid errors during initial migration
            if (Schema::hasTable('settings')) {
                \Illuminate\Support\Facades\View::composer(
                    ['partials.landing.nav', 'partials.landing.footer'],
                    function ($view) {
                        $settings = \App\Models\Setting::whereIn('key', [
                            'social_facebook',
                            'social_instagram',
                            'social_tiktok',
                            'company_phone',
                            'company_email',
                            'company_address'
                        ])->pluck('value', 'key');

                        $view->with([
                            'social_facebook' => $settings['social_facebook'] ?? '#',
                            'social_instagram' => $settings['social_instagram'] ?? '#',
                            'social_tiktok' => $settings['social_tiktok'] ?? '#',
                            'company_email' => $settings['company_email'] ?? 'finance@rcgo.id',
                            'company_address' => $settings['company_address'] ?? 'Jl. RC Veteran No. 88, Bintaro, Jakarta Selatan',
                            'company_phone' => $settings['company_phone'] ?? '+62 812-3456-7890',
                        ]);
                    }
                );
            }
        } catch (\Exception $e) {
            // Ignored to prevent crashes if DB connection fails
        }
    }
}
