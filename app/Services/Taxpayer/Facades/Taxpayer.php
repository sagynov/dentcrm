<?php
namespace App\Services\Taxpayer\Facades;

use App\Services\Taxpayer\Repositories\TaxpayerInterface;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\Taxpayer\Repositories\TaxpayerRepository
 */
class Taxpayer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TaxpayerInterface::class;
    }
}