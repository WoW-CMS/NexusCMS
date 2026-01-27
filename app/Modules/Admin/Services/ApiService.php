<?php

namespace Modules\Admin\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected string $baseUrl = 'http://wowcms-apiwowcms-rtpsnl-03dd1f-188-245-113-32.traefik.me/api/github';

    public function latestRelease(): array
    {
        $response = Http::timeout(5)->acceptJson()->get($this->baseUrl . '/wow-cms/nexuscms/latest');

        if (!$response->ok()) {
            return [];
        }

        $data = $response->json();

        return is_array($data) ? $data : [];
    }

    public function checkVersion(string $owner, string $repo, string $current): array
    {
        $response = Http::timeout(5)->acceptJson()->get("{$this->baseUrl}/{$owner}/{$repo}/check-version", [
            'current' => $current,
        ]);

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
