<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Assessment; // (ปรับพาธให้ตรงกับที่เก็บ Model ของคุณ)


class AssessmentController extends Controller
{
    //
    public function def(){
        $assessmentLogs = Assessment::all();
        return view('admin.assessment_def',compact('assessmentLogs'));
    }

    public function predict(Request $request)
    {
        // 1. Validate ข้อมูลก่อนส่งไป
        $validated = $request->validate([
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required',
        ]);

        try {
            // 2. ส่งไปที่ Flask ด้วย Laravel HTTP Client
            $response = Http::timeout(60)->post('https://model-sfzt.onrender.com/predict', $validated);

            // 3. เช็คว่า Flask ตอบสำเร็จไหม (ย้ายมาเช็คก่อน)
            if ($response->failed()) {
                Log::warning('Flask prediction failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'category' => '',
                    'confidence' => 0,
                    'error' => 'API ประมวลผลล้มเหลว หรือ ตอบกลับมาผิดพลาด',
                ], 200); 
            }

            // 4. มั่นใจแล้วว่า API ตอบกลับมาสำเร็จ (200) ค่อยแปลงเป็น Array
            $array = $response->json(); 

            $data = [
                'q1' => $request->q1,
                'q2' => $request->q2,
                'q3' => $request->q3,
                'q4' => $request->q4,
                'q5' => $request->q5,
                'predicted_mood' => $array['predicted_mood'] ?? null,
                'confidence' => $array['confidence'] ?? null,
                'created_at' => now()
            ];   

            // บันทึกลง Database
            Assessment::create($data);

            // 5. ส่งผลลัพธ์จาก Flask กลับไปตรงๆ (ใช้ $array ได้เลย ไม่ต้อง ->json() ซ้ำ)
            return response()->json($array);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // กรณี connect ไม่ได้เลย (server ปิด/ timeout)
            Log::error('Cannot connect to Flask server: ' . $e->getMessage());

            return response()->json([
                'category' => 'focus',
                'confidence' => 0,
                'error' => 'ยังไม่ได้เปิด Python Server กรุณาเปิด CMD แล้วรันคำสั่ง python app.py',
            ], 200);
        } catch (\Exception $e) {
            // (เพิ่มให้) กรณีเกิด Error อื่นๆ ที่ไม่คาดคิด เช่น Database บันทึกไม่ได้
            Log::error('Unexpected error in predict: ' . $e->getMessage());

            return response()->json([
                'category' => '',
                'confidence' => 0,
                'error' => 'เกิดข้อผิดพลาดบางอย่างในระบบ',
            ], 200);
        }
    }
}
