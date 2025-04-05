<?php
namespace App\Services\Halyk\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use App\Services\Halyk\Exceptions\HalykInvoiceException;
use Illuminate\Support\Facades\Hash;

class HalykInvoiceRepository
{
    protected PendingRequest $http;
    private $useragent = 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36';
    
    public function create($invoiceId, $amount)
    {
        $client_id = env('API_HALYK_CLIENT_ID');
        $client_secret = env('API_HALYK_CLIENT_SECRET');
        $secret_hash = env('API_HALYK_SECRET_HASH');
        $hash = Hash::make($invoiceId.$amount.$secret_hash);
        $terminal = env('API_HALYK_TERMINAL');
        $halyk_url = env('API_HALYK_TOKEN_URL');
        $response = Http::asForm()->withUserAgent($this->useragent)
            ->withBasicAuth($client_id, $client_secret)
            ->post($halyk_url, [
                'grant_type' => 'client_credentials',
                'scope' => 'webapi usermanagement email_send verification statement statistics payment',
                'client_id' => $client_id,
                'client_secret'=> $client_secret,
                'invoiceID' => $invoiceId,
                'secret_hash' => $hash,
                'amount' => $amount,
                'currency' => 'KZT',
                'terminal' => $terminal,
                'postLink' => '',
                'failurePostLink' => ''
            ]);
        throw_if($response->failed(), HalykInvoiceException::class, 'Unable to fetch halykInvoice data');
        $auth = $response->json();
        throw_if(!isset($auth['access_token']), HalykInvoiceException::class, 'Unable to find access_token from halykInvoice data');

        return [
            'auth' => $auth,
            'invoiceId' => $invoiceId,
            'amount' => $amount,
            'backLink' => route('owner.payments.success'),
            'autoBackLink' => true,
            'failureBackLink' => route('owner.payments.fail'),
            'postLink' => env('API_HALYK_POSTLINK'),
            'language' => 'rus',
            'terminal' => env('API_HALYK_TERMINAL'),
            'currency' => 'KZT'
        ];
    }
}