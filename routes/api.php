<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssessmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| ไฟล์นี้ไม่ผ่าน CSRF middleware (ต่างจาก routes/web.php)
| Laravel จะเติม prefix "/api" ให้อัตโนมัติ
| ดังนั้น route นี้จะเรียกจริงที่ URL: /api/predict
| ห้ามเขียน 'api/predict' ซ้ำในนี้ ไม่งั้นจะกลายเป็น /api/api/predict
|
*/

Route::post('/predict', [AssessmentController::class, 'predict']);