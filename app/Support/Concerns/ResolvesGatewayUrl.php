<?php

namespace App\Support\Concerns;

trait ResolvesGatewayUrl
{
    public function endpointUrl(string $path): string
    {
        $baseUrl = rtrim((string) config('gateway.base_url', config('services.shapi_auth.base_url', 'http://shapi-qq0p.onrender.com')), '/');
        $normalizedPath = '/'.ltrim($path, '/');

        return $baseUrl.$normalizedPath;
    }
}
