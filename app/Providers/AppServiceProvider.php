<?php

namespace App\Providers;

use App\Services\Taxpayer\Repositories\TaxpayerInterface;
use App\Services\Taxpayer\Repositories\TaxpayerRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaxpayerInterface::class, TaxpayerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
