<?php

namespace App\Http\Controllers;

use App\Services\B2Client;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AudioStreamController extends Controller
{
    public function stream(Request $request, B2Client $b2, string $filename): StreamedResponse
    {
        $url = $b2->fileDownloadUrl($filename);
        $token = $b2->authorizationToken();

        $headers = ['Authorization' => $token];

        // สำคัญ: forward Range header เพื่อรองรับ seek bar / iOS partial content
        if ($request->hasHeader('Range')) {
            $headers['Range'] = $request->header('Range');
        }

        $client = new Client();

        try {
            $upstream = $client->request('GET', $url, [
                'headers' => $headers,
                'stream' => true,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // token หมดอายุ (401) → refresh แล้วลองใหม่ 1 ครั้ง
            if ($e->getResponse()->getStatusCode() === 401) {
                $b2->refresh();
                $headers['Authorization'] = $b2->authorizationToken();
                $upstream = $client->request('GET', $url, [
                    'headers' => $headers,
                    'stream' => true,
                ]);
            } else {
                abort(404, 'Audio file not found');
            }
        }

        $status = $upstream->getStatusCode(); // 200 หรือ 206 (Partial Content)
        $body = $upstream->getBody();

        $responseHeaders = [
            'Content-Type' => $upstream->getHeaderLine('Content-Type') ?: 'audio/mpeg',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=86400',
        ];

        if ($upstream->hasHeader('Content-Length')) {
            $responseHeaders['Content-Length'] = $upstream->getHeaderLine('Content-Length');
        }
        if ($upstream->hasHeader('Content-Range')) {
            $responseHeaders['Content-Range'] = $upstream->getHeaderLine('Content-Range');
        }

        return response()->stream(function () use ($body) {
            while (!$body->eof()) {
                echo $body->read(1024 * 64); // 64KB ต่อ chunk
                ob_flush();
                flush();
            }
        }, $status, $responseHeaders);
    }
}