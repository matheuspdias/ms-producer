<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class HttpClientService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.consumer.url');
    }

    public function get(string $endpoint): array
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->baseUrl}/{$endpoint}");

            if ($response->failed()) {
                throw new Exception("HTTP request failed with status {$response->status()}");
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('HTTP Client Error', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);

            throw new Exception("Erro ao fazer requisição: {$e->getMessage()}");
        }
    }
}
