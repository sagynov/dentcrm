<?php
namespace App\Services\Halyk\Facades;

use App\Services\Halyk\Repositories\HalykInvoiceRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\HalykInvoice\Repositories\HalykInvoiceRepository
 */
class HalykInvoice extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HalykInvoiceRepository::class;
    }
}