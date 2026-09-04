<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class AppController extends Controller
{
    // Function สำหรับนำข้อมูลใน ตาราง application ไปแสดงผลที่หน้า ให้กับ Admin
    public function def(){
        $apps = Application::all();
        return view('admin.app_def',compact('apps'));
    }

    // Function สำหรับในการแสดงรายละเอียดข้อมูลใน ตาราง application ตาม id ที่ส่งมา
    public function det($id){
        $app = Application::find($id);
        if($app){
            return view('admin.app_det',compact('app'));
        }
        else{
            return redirect()->route('app.def');
        }
    }

    // Function สำหรับเรียกข้อมูล ตาราง application ตาม id มาเตรียมแก้ไขข้อมูล
    public function edit($id = null){
        $app = Application::findOrNew($id);
        return view('admin.app_edit',compact('app'));
    }

    // Function สำหรับการบันทึกข้อมูลลงใน ตาราง application
    public function save(Request $request ,$id = null) 
    {
        // การกำหนดรูปแบบข้อมูลที่กรอก 
        $request->validate([
        'name'   => 'required|max:50',
        'dir'    => 'required|max:50', 
        'detail' => ''
                      
        ], [
        // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
        'name.required' => 'กรุณากรอกชื่อแอปพลิเคชันด้วยครับ',
        'name.max' => 'กรอกไม่เกิน 50 ตัวอักษร',
        'dir.required' => 'กรุณากรอกด้วยครับ',
        'dir.max' => 'กรอกไม่เกิน 50 ตัวอักษร',
        
        ]);

        // การบันทึกข้อมูลลงในตาราง application (โดยที่ id เท่ากับค่า null จะเป็นการเพิ่มข้อมูล แต่ถ้าไม่ใช่จะเป็นการอัพเดตข้อมูล ตามหมายเลข id)
        $app = Application::findOrNew($id);
        $app->name     = $request->name;
        $app->dir      = $request->dir;
        if(empty($id)){
            $app->status  = 1;
        }
        $app->save();
        return redirect()->route('app.det',$app->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
        
    }

    // Function สำหรับในการเปลี่ยนสถานะข้อมูลใน ตาราง application ในเป็น Active หรือ In Active 
     public function del($id) {
        $app = Application::find($id); 

        if (empty($id)) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลผู้ใช้งานที่ต้องการแก้ไข');
        }

        $app->status = $app->status == 1 ? 0 : 1;
        $app->save();
        $message = $app->status == 1 ? 'เปลี่ยนสถานะเป็น Active เรียบร้อย' : 'เปลี่ยนสถานะเป็น Inactive เรียบร้อย';
        return redirect()->back()->with('success', $message);
    }
}
