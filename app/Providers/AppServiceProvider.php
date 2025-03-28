<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ClinicPolicy;
use App\Services\Taxpayer\Repositories\TaxpayerInterface;
use App\Services\Taxpayer\Repositories\TaxpayerRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\\d{11}$/', $value);
        });
        Gate::define('owner', function (User $user) {
            return $user->is_owner;
        });
        Gate::define('doctor', function (User $user) {
            return $user->is_doctor;
        });
    }
}
