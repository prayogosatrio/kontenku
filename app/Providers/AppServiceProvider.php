<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pagination custom berupa teks/link biasa (project ini tidak memakai Tailwind CSS).
        Paginator::defaultView('vendor.pagination.custom');
    }
}
