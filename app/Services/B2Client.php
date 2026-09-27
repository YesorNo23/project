<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class B2Client
{
    public function authData(): array
    {
        return Cache::remember('b2_auth_data', now()->addHours(20), function () {
            $keyId = config('services.backblaze.key_id');
            $appKey = config('services.backblaze.application_key');

            $response = Http::withBasicAuth($keyId, $appKey)
                ->get('https://api.backblazeb2.com/b2api/v3/b2_authorize_account');

            if ($response->failed()) {
                throw new RuntimeException('B2 authorize failed: ' . $response->body());
            }

            $data = $response->json();

            return [
                'authorizationToken' => $data['authorizationToken'],
                'downloadUrl' => $data['apiInfo']['storageApi']['downloadUrl'],
                'apiUrl' => $data['apiInfo']['storageApi']['apiUrl'],
            ];
        });
    }

    public function fileDownloadUrl(string $filename): string
    {
        $auth = $this->authData();
        $bucketName = config('services.backblaze.bucket_name');

        return $auth['downloadUrl'] . '/file/' . $bucketName . '/' . ltrim($filename, '/');
    }

    public function authorizationToken(): string
    {
        return $this->authData()['authorizationToken'];
    }

    /** เรียกใช้ตอน token หมดอายุ/ถูก B2 reject (401) — เคลียร์ cache แล้ว authorize ใหม่ */
    public function refresh(): array
    {
        Cache::forget('b2_auth_data');
        return $this->authData();
    }
}