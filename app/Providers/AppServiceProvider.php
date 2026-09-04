<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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

    public function boot()
{
    // บังคับให้ asset() สร้างลิงก์เป็น https เมื่ออยู่บนโปรดักชัน
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }
}
}
