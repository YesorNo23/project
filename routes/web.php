<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PlayListController;
use App\Http\Controllers\AuthController;

// เส้นทางไปยังหน้า Dashbord ของ admin
Route::get('admin',[HomePageController::class,'adminDashbord'])->middleware('auth')->middleware('CheckAcl:MusicManagement,1')->name('admin.home');

// เส้นทางในการรับส่ง แสดงผล และบันทึก ของข้อมูล Users ทั้งหมดในระบบ
Route::get('admin/user/def/',[UserController::class,'def'])->middleware('auth')->middleware('CheckAcl:UserManagement,1')->name('user.def'); // เส้นทางไปยังหน้าตารางข้อมูลของผู้ใช้ในระบบ users.blade.php
Route::get('admin/user/det/{id}',[UserController::class,'det'])->middleware('auth')->middleware('CheckAcl:UserManagement,1')->name('user.det'); // เส้นทางไปยังหน้ารายละเอียดข้อมูลผู้ใช้ ตาม id ที่ส่งไป
Route::get('admin/user/editinfo/{id?}',[UserController::class,'editinfo'])->middleware('auth')->middleware('CheckAcl:UserManagement,2')->name('user.editinfo'); // เส้นทางไปยังหน้าแก้ข้อมูลผู้ใช้ ของ admin ตาม id ที่ส่งไป
Route::get('admin/user/editpass/{id?}',[UserController::class,'editpass'])->middleware('CheckAcl:UserManagement,2')->middleware('auth')->name('user.editpass'); // เส้นทางไปยังหน้าแก้รหัสผ่านของผู้ใช้ ของ admin ตาม id ที่ส่งไป
Route::post('admin/user/save/{id?}',[UserController::class,'save'])->middleware('CheckAcl:UserManagement,2')->middleware('auth')->name('user.save'); // เส้นทางในการบันทึก เพิ่ม ข้อมูลของ User ในระบบ
Route::post('admin/user/savepass/{id}',[UserController::class,'savepass'])->middleware('CheckAcl:UserManagement,2')->middleware('auth')->name('user.savepass'); // เส้นทางในการบันทึกรหัสผ่านของ User ในระบบ
Route::get('admin/user/del/{id}',[UserController::class,'del'])->middleware('CheckAcl:UserManagement,2')->middleware('auth')->name('user.del'); // เส้นทางในการเปลี่ยนสถานะของผู้ใช้ ให้เป็น Active หรือ In Actives ตาม id ที่ส่ง

// เส้นทางในการรับส่ง แสดงผล และบันทึก ของข้อมูล User Group ทั้งหมดในระบบ
Route::get('admin/group/def',[UserGroupController::class,'def'])->middleware('CheckAcl:GroupManagement,1')->middleware('auth')->name('group.def'); // เส้นทางไปยังหน้าตารางข้อมูลของกลุ่มผู้ใช้ในระบบ group_def.blade.php
Route::get('admin/group/det/{id}',[UserGroupController::class,'det'])->middleware('CheckAcl:GroupManagement,1')->middleware('auth')->name('group.det'); // เส้นทางไปยังหน้ารายละเอียดข้อมูลกลุ่มผู้ใช้ ตาม id ที่ส่งไป group_det.blade.php
Route::get('admin/group/edit/{id?}',[UserGroupController::class,'edit'])->middleware('CheckAcl:GroupManagement,2')->middleware('auth')->name('group.edit'); // เส้นทางไปหน้าแก้ไขข้อมูลของกลุ่มผู้ใช้ ตาม id ที่ส่ง group_edit.blade.php
Route::post('admin/group/save/{id?}',[UserGroupController::class,'save'])->middleware('CheckAcl:GroupManagement,2')->middleware('auth')->name('group.save'); // เส้นทางในการบันทึก เพิ่ม ข้อมูลของ User Group ในระบบ
Route::get('admin/group/del/{id?}',[UserGroupController::class,'del'])->middleware('CheckAcl:GroupManagement,2')->middleware('auth')->name('group.del'); // เส้นทางในการเปลี่ยนสถานะของกลุ่มผู้ใช้ ให้เป็น Active หรือ In Actives ตาม id ที่ส่ง

// เส้นทางในการรับส่ง แสดงผล และบันทึก ของข้อมูล category ทั้งหมดในระบบ
Route::get('admin/filter/def',[FilterController::class,'def'])->middleware('CheckAcl:FilterManagement,1')->middleware('auth')->name('filter.def'); // เส้นทางไปยังหน้าตารางข้อมูลของตัวกรอง filter_def.blade.php
Route::get('admin/filter/det/{id?}',[FilterController::class,'det'])->middleware('CheckAcl:FilterManagement,1')->middleware('auth')->name('filter.det'); // เส้นทางไปยังหน้ารายละเอียดข้อมูลตัวกรอง ตาม id ที่ส่งไป filter_det.blade.php
Route::get('admin/filter/edit/{id?}',[FilterController::class,'edit'])->middleware('CheckAcl:FilterManagement,2')->middleware('auth')->name('filter.edit'); // เส้นทางไปหน้าแก้ไขข้อมูลตัวกรอง ตาม id ที่ส่ง filter_edit.blade.php
Route::post('admin/filter/save/{id?}',[FilterController::class,'save'])->middleware('CheckAcl:FilterManagement,2')->middleware('auth')->name('filter.save'); // เส้นทางในการบันทึก เพิ่ม ข้อมูลของตัวกรอง ในระบบ
Route::get('admin/filter/del/{id?}',[FilterController::class,'del'])->middleware('CheckAcl:FilterManagement,1')->middleware('auth')->name('filter.del'); // เส้นทางในการเปลี่ยนสถานะของตัวกรอง ให้เป็น Active หรือ In Actives ตาม id ที่ส่ง

// เส้นทางในการรับส่ง แสดงผล และบันทึก ของข้อมูล Application ทั้งหมดในระบบ
Route::get('admin/app/def',[AppController::class,'def'])->middleware('CheckAcl:AppManagement,1')->middleware('auth')->name('app.def'); // เส้นทางไปยังหน้าตารางข้อมูลของแอปพิเคชั่น app_def.blade.php
Route::get('admin/app/det/{id}',[AppController::class,'det'])->middleware('CheckAcl:AppManagement,1')->middleware('auth')->name('app.det'); // เส้นทางไปยังหน้ารายละเอียดข้อมูลแอปพิเคชั่น ตาม id ที่ส่งไป app_det.blade.php
Route::get('admin/app/edit/{id?}',[AppController::class,'edit'])->middleware('CheckAcl:AppManagement,2')->middleware('auth')->name('app.edit'); // เส้นทางไปหน้าแก้ไขข้อมูลแอปพิเคชั่น ตาม id ที่ส่ง app_edit.blade.php
Route::post('admin/app/save/{id?}',[AppController::class,'save'])->middleware('CheckAcl:AppManagement,2')->middleware('auth')->name('app.save'); // เส้นทางในการบันทึก เพิ่ม ข้อมูลขอแอปพิเคชั่น ในระบบ
Route::get('admin/app/del/{id}',[AppController::class,'del'])->middleware('CheckAcl:AppManagement,2')->middleware('auth')->name('app.del'); // เส้นทางในการเปลี่ยนสถานะของแอปพิเคชั่น ให้เป็น Active หรือ In Actives ตาม id ที่ส่ง

// เส้นทางในการรับส่ง แสดงผล และบันทึก ของข้อมูล Music(คลื่นเสียง) ทั้งหมดในระบบ
Route::get('admin/music/def',[MusicController::class,'def'])->middleware('CheckAcl:MusicManagement,1')->middleware('auth')->name('music.def');
Route::get('admin/music/det/{id?}',[MusicController::class,'det'])->middleware('CheckAcl:MusicManagement,1')->middleware('auth')->name('music.det');
Route::get('admin/music/edit/{id?}',[MusicController::class,'edit'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('music.edit'); // เส้นทางไปหน้าแก้ไขข้อมูลคลื่นเสียง ตาม id ที่ส่ง music_edit.blade.php
Route::post('admin/music/save/{id?}',[MusicController::class,'save'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('music.save'); // เส้นทางในการบันทึก เพิ่ม ข้อมูลขอคลื่นเสียง ในระบบ
Route::get('admin/music/del/{id}',[MusicController::class,'del'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('music.del'); // เส้นทางในการเปลี่ยนสถานะของคลื่นเสียง ให้เป็น Active หรือ In Actives ตาม id ที่ส่ง

// เส้นทางในการรับส่ง แสดงผล และบันทึก ของข้อมูล Music(คลื่นเสียง) ทั้งหมดในระบบ
Route::get('admin/history/def',[HistoryController::class,'def'])->middleware('auth')->name('history.def'); // เส้นทางในแสดงข้อมูล ประวัติการฟังคลื่นเสียงของผู้ใช้ทั้งหมด history_def.blade.php
Route::get('admin/history/det/{id}',[HistoryController::class,'det'])->middleware('auth')->name('history.det'); // เส้นทางไปยังหน้ารายละเอียดข้อมูลประวัติการฟังคลื่นเสียงของผู้ใช้ ตาม id ที่ส่งไป app_det.blade.php
Route::post('history/save',[HistoryController::class,'save'])->name('history.save'); // เส้นทางในการบันทึกประวัติการฟังคลื่นเสียงของผู้ใช้

Route::get('admin/playlist/def',[PlayListController::class,'def'])->middleware('CheckAcl:MusicManagement,1')->middleware('auth')->name('playlist.def'); // เส้นทางในแสดงข้อมูล ประวัติการฟังคลื่นเสียงของผู้ใช้ทั้งหมด
Route::get('admin/playlist/det/{id?}',[PlayListController::class,'det'])->middleware('CheckAcl:MusicManagement,1')->middleware('auth')->name('playlist.det');
Route::get('admin/playlist/edit/{id?}',[PlayListController::class,'edit'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('playlist.edit');
Route::post('admin/playlist/save/{id?}',[PlayListController::class,'save'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('playlist.save');
Route::get('admin/playlist/manage/{id?}',[PlayListController::class,'manage'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('playlist.manage');
Route::post('admin/playlist/update/{id?}',[PlayListController::class,'update'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('playlist.update');
Route::get('admin/playlist/del/{id}',[PlayListController::class,'del'])->middleware('CheckAcl:MusicManagement,2')->middleware('auth')->name('playlist.del');

Route::get('admin/log/def',[LogController::class,'def'])->middleware('auth')->name('log.def'); // เส้นทางในแสดงข้อมูล ประวัติการใช้งานระบบทั้งหมด


//หน้า login เข้าใช้งาน
Route::get('login',[AuthController::class,'loginform'])->name('login'); // เส้นทางไปยังหน้า Login เข้าใช้งาน
Route::post('login',[AuthController::class,'login']); // เส้นทางไป Login เข้าใช้งาน
Route::post('logout',[AuthController::class,'logout'])->name('logout'); // เส้นทางไป Logout

//หน้า สมัครสมาชิก
Route::get('/register', function () {return view('register');})->name('regisfrom'); // เส้นทางไปยังหน้าสมัครสมาชิก
Route::post('/register/save',[UserController::class,'register'])->name('register'); // เส้นทางไป Logout


Route::get('/', function () {return view('user.index');})->name('homepage');  // เส้นทางไปยังหน้าแรกของเว็ปไซต์
Route::get('music/{category}', function () {return view('user.music');})->name('musicpage');  // เส้นทางไปยังหน้าประเภทคลืนเสียง user/music.blade.php
Route::get('history', function () {return view('user.history');})->middleware('auth')->name('historypage');  // เส้นทางไปยังหน้าประวัติการฟังคลื่นเสียงของผู้ใช user/history.blade.php
Route::get('playlist', function () {return view('user.playlist');})->middleware('auth')->name('playlistpage');  // เส้นทางไปยังหน้าแรกของเว็ปไซต์
Route::get('playlist/{id}', function ($id) {return view('user.playlist_detail', ['id' => $id]);})->middleware('auth')->name('playlist.datail');
Route::get('wave', function () {return view('user.wave');})->middleware('auth')->name('wavepage');  // เส้นทางไปยังหน้าแรกของเว็ปไซต์


// api สำหรับ เรียกข้อมูลให้กับ Front-End
Route::get('get_songs/{category?}',[MusicController::class,'get_songs']); // เส้นทางในการเรียกข้อมูลคลื่นเสียง
Route::get('get_history',[HistoryController::class,'get_history'])->middleware('auth'); // เส้นทางในการเรียกข้อมูลประวัติการฟัง
Route::get('get_playlists',[PlayListController::class,'get_playlists'])->middleware('auth'); // เส้นทางในการเรียกข้อมล playlist
Route::get('/get_playlist/{id}', [PlayListController::class, 'get_playlist'])->middleware('auth');

Route::post('history/save',[HistoryController::class,'save']); // เส้นทางในการบันทึกประวัติการฟังของผู้ใช
Route::put('/profile',[UserController::class,'updateprofile'])->middleware('auth')->name('profile.update'); // เส้นทางในการเรียกข้อมูลของผู้ใช้
Route::put('/password',[UserController::class,'changepassword'])->middleware('auth')->name('password.change'); // เส้นทางในการเปลี่ยนรหัสผ่านของผู้ใช้
Route::put('update_playlist/{id?}',[PlayListController::class,'update_playlist'])->middleware('auth')->name('playlist.update'); // เส้นทางในการเปลี่ยนรหัสผ่านของผู้ใช้
Route::post('/add_songs_to_playlist/{id}',[PlayListController::class,'add_songs_to_playlist'])->middleware('auth')->name('songs.add'); // เส้นทางในการเปลี่ยนรหัสผ่านของผู้ใช้
Route::post('/delete_playlist/{id}',[PlayListController::class,'delete_playlist'])->middleware('auth')->name('playlist.delete'); // เส้นทางในการเปลี่ยนรหัสผ่านของผู้ใช้

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/init-database-now', function () {
    try {
        // 1. เคลียร์ Cache ระบบ
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        // 2. ปิด FK Checks แล้วสั่ง Wipe DB + Migrate + Seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Artisan::call('migrate:fresh', [
            '--force' => true
        ]);

        // 3. สร้างตาราง sessions เผื่อไว้ในฐานข้อมูลด้วย
        if (!Schema::hasTable('sessions')) {
            Artisan::call('session:table');
            Artisan::call('migrate', ['--force' => true]);
        }

        // 4. รัน Seeder
        Artisan::call('db:seed', [
            '--force' => true
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return '<h1>SUCCESS!</h1><p>Database and Sessions table created, seeded successfully.</p>';
    } catch (\Exception $e) {
        return '<h1>ERROR!</h1><p>' . $e->getMessage() . '</p>';
    }
});