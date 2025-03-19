<?php
namespace App\Services\Taxpayer\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

use function Pest\Laravel\withHeaders;

class ApiBccRepository
{
    protected PendingRequest $http;
    private $token;
    private $base_url = 'https://api-test.bcc.kz/bcc/production';

    public function __construct()
    {
        // Globaly set the base url and accept json
        $this->token = $this->getToken();
        if(!$this->token) {
            $this->regenerate();
        }
        $this->http = Http::baseUrl($this->base_url)
            ->withToken($this->token)->acceptJson();
    }

    public function getToken()
    {
        return cache('api_bcc_token');
    }
    public function setToken($token)
    {
        $this->token = $token;
        cache(['api_bcc_token' => $token], now()->addMinutes(55));
    }

    public function regenerate()
    {
        $client_id = env('API_BCC_CLIENT_ID');
        $client_secret = env('API_BCC_CLIENT_SECRET');
        $response = Http::baseUrl($this->base_url)->asForm()->withUserAgent(fake()->userAgent())
            ->withBasicAuth($client_id, $client_secret)
            ->post('/v2/oauth/token', [
                'grant_type' => 'client_credentials',
                'scope' => 'bcc.application.informational.api'
            ]);
        $json = $response->json();
        $this->setToken($json['access_token']);
    }
}