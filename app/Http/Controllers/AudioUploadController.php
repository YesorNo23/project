<?php

namespace App\Http\Controllers;

use App\Services\B2Client;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AudioUploadController extends Controller
{
    private const ALLOWED_EXT = ['mp3', 'm4a', 'aac', 'wav', 'ogg', 'flac'];
    private const MAX_SIZE = 120 * 1024 * 1024; // 200MB

    /**
     * @param string      $localPath path ไฟล์ในเครื่อง เช่น /tmp/phpXXXX หรือ storage_path('app/x.mp3')
     * @param string      $extension นามสกุลไฟล์ เช่น "mp3"
     * @param string|null $mime      ถ้าไม่ส่งจะตรวจจากไฟล์ให้เอง
     * @param string      $folder    โฟลเดอร์ปลายทางใน bucket
     */
    public function store(
        B2Client $b2,
        string $localPath,
        string $extension,
        ?string $mime = null,
        string $folder = 'songs'
    ): array {
        $extension = strtolower(ltrim($extension, '.'));

        if (!is_file($localPath)) {
            throw new InvalidArgumentException('ไม่พบไฟล์: ' . $localPath);
        }
        if (!in_array($extension, self::ALLOWED_EXT, true)) {
            throw new InvalidArgumentException('ชนิดไฟล์ไม่รองรับ: ' . $extension);
        }
        if (filesize($localPath) > self::MAX_SIZE) {
            throw new InvalidArgumentException('ไฟล์ใหญ่เกิน 120MB');
        }

        // ตั้งชื่อใหม่กันชื่อซ้ำ/ภาษาไทย/อักขระแปลก
        $remoteName = trim($folder, '/') . '/' . Str::uuid() . '.' . $extension;

        $result = $b2->uploadFile($localPath, $remoteName, $mime);

        return [
            'message' => 'อัปโหลดสำเร็จ',
            'filename' => $result['fileName'],
            'file_id' => $result['fileId'],
            'stream_url' => url('/audio/' . $result['fileName']), // ปรับตาม route stream ของคุณ
        ];
    }
}