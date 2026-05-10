<?php

namespace Modules\Admin\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class ApiService
{
    protected string $baseUrl;
    protected ?string $apiKey;
    protected string $apiKeyHeader;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.nexus_api.url', 'https://api.wow-cms.com/api/github'), '/');
        $this->apiKey = config('services.nexus_api.key');
        $this->apiKeyHeader = config('services.nexus_api.header', 'X-API-Key');
    }

    protected function http()
    {
        $client = Http::timeout(5)->acceptJson();
        if (!empty($this->apiKey)) {
            $client = $client->withHeaders([$this->apiKeyHeader => $this->apiKey]);
        }
        return $client;
    }

    public function latestRelease(): array
    {
        try {
            $response = $this->http()->get($this->baseUrl . '/wow-cms/nexuscms/latest');
        } catch (ConnectionException) {
            return [];
        }

        if (!$response->ok()) {
            return [];
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    public function checkVersion(string $owner, string $repo, string $current): array
    {
        try {
            $response = $this->http()->get("{$this->baseUrl}/{$owner}/{$repo}/check-version", [
                'current' => $current,
            ]);
        } catch (ConnectionException) {
            return [];
        }

        if (!$response->ok()) {
            return [];
        }

        $data = $response->json();

        if (!is_array($data)) {
            return [];
        }

        return [
            'current'  => $data['current']  ?? '',
            'latest'   => $data['latest']   ?? '',
            'outdated' => $data['outdated'] ?? false,
        ];
    }
}
