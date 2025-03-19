<?php

namespace App\Services\Taxpayer\Repositories;

interface TaxpayerInterface
{
    public function findByIIN(string $iin): array;
}
