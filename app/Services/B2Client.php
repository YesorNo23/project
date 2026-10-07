<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use GuzzleHttp\Psr7\Utils;

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

    

    /** ขอ upload URL + upload token (ใช้ได้ต่อ 1 ไฟล์/ครั้ง แนะนำขอใหม่ทุกครั้ง) */
    public function getUploadUrl(): array
    {
        $auth = $this->authData();

        $response = Http::withHeaders(['Authorization' => $auth['authorizationToken']])
            ->get($auth['apiUrl'] . '/b2api/v3/b2_get_upload_url', [
                'bucketId' => config('services.backblaze.bucket_id'),
            ]);

        if ($response->status() === 401) {
            // token หมดอายุ → authorize ใหม่แล้วลองอีกครั้ง
            $auth = $this->refresh();
            $response = Http::withHeaders(['Authorization' => $auth['authorizationToken']])
                ->get($auth['apiUrl'] . '/b2api/v3/b2_get_upload_url', [
                    'bucketId' => config('services.backblaze.bucket_id'),
                ]);
        }

        if ($response->failed()) {
            throw new RuntimeException('B2 get_upload_url failed: ' . $response->body());
        }

        return $response->json(); // uploadUrl, authorizationToken
    }

    /**
     * อัปโหลดไฟล์ขึ้น B2
     *
     * @param string $localPath path ไฟล์ในเครื่อง (เช่น $file->getRealPath())
     * @param string $remoteName ชื่อไฟล์ใน bucket (เช่น "songs/abc.mp3")
     */
    public function uploadFile(string $localPath, string $remoteName, ?string $mime = null): array
    {
        $remoteName = ltrim($remoteName, '/');
        $sha1 = sha1_file($localPath);
        $size = filesize($localPath);
        $mime ??= mime_content_type($localPath) ?: 'b2/x-auto';

        $attempts = 0;
        do {
            $attempts++;
            $upload = $this->getUploadUrl();

            $response = Http::withHeaders([
                    'Authorization' => $upload['authorizationToken'],
                    'X-Bz-File-Name'    => rawurlencode($remoteName),   // แก้จาก X-Bx-File-Name
                    'X-Bz-Content-Sha1' => $sha1,                       // แก้จาก X-Bx-Content-Sha1
                    'Content-Length' => $size,
                ])
                ->timeout(300)
                ->withBody(Utils::streamFor(fopen($localPath, 'rb')), $mime)
                ->post($upload['uploadUrl']);

            // 401/5xx → ขอ upload URL ใหม่แล้วลองซ้ำ (B2 แนะนำให้ทำแบบนี้)
            $retry = $response->status() === 401 || $response->serverError();
        } while ($retry && $attempts < 3);

        if ($response->failed()) {
            throw new RuntimeException('B2 upload failed: ' . $response->body());
        }

        return $response->json(); // fileId, fileName, contentLength, ...
    }
}