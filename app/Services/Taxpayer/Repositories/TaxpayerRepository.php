<?php
namespace App\Services\Taxpayer\Repositories;

use App\Services\Taxpayer\Exceptions\TaxpayerException;

class TaxpayerRepository extends ApiBccRepository implements TaxpayerInterface
{
    public function findByIIN(string $iin): array
    {
        $response = $this->http->get('/v1/public/kgd/taxPayerInfo', [
            'iin' => $iin,
        ]);

        throw_if($response->failed(), TaxpayerException::class, 'Unable to fetch taxpayer data');

        $data = $response->json();
        throw_if(!isset($data['title']), TaxpayerException::class, 'Unable to fetch taxpayer data');
        $names = explode(' ', $data['title']);


        return [
            'last_name'=> @$names[0],
            'first_name' => @$names[1],
            'middle_name'=> @$names[2],
        ];
    }
}