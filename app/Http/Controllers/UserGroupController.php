<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\Application;

class UserGroupController extends Controller
{
    // Function สำหรับนำข้อมูลใน ตาราง usergroup ไปแสดงผลที่หน้า ให้กับ Admin
    public function def(){
        $groups = UserGroup::all();
        return view('admin.group_def',compact('groups'));
    }

    // Function สำหรับในการแสดงรายละเอียดข้อมูลใน ตาราง usergroup ตาม id ที่ส่งมา
    public function det($id){
        $group = UserGroup::find($id);
        return view('admin.group_det',compact('group'));
        
    }

    // Function สำหรับเรียกข้อมูล ตาราง usergroup ตาม id มาเตรียมแก้ไขข้อมูล
    public function edit($id = null){
        $group = UserGroup::find($id);
        $apps = Application::all();
        return view('admin.group_edit',compact('group','apps'));
    }

    // Function สำหรับการบันทึกข้อมูลลงใน ตาราง usergroup
    public function save(Request $request ,$id = null) 
    {
        // การกำหนดรูปแบบข้อมูลที่กรอก 
        $request->validate([
        'name'   => 'required|max:50', 
        'detail' => 'max:50', 
        
        ], [
        
        // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
        'name.required' => 'กรุณากรอกชื่อกลุ่มผู้ใช้ผู้ใช้งานด้วยครับ',
        'name.max'      => 'กรอกไม่เกิน 50 ตัวอักษร',
        'detail.max'    => 'กรอกไม่เกิน 50 ตัวอักษร',
        ]);

        // การบันทึกข้อมูลลงในตาราง usergroup (โดยที่ id เท่ากับค่า null จะเป็นการเพิ่มข้อมูล แต่ถ้าไม่ใช่จะเป็นการอัพเดตข้อมูล ตามหมายเลข id)
        $group = UserGroup::findOrNew($id);
        $group->name     = $request->name;
        $group->detail  = $request->detail;
        if(!$id){
            $group->status  = 1;
        }
        $group->save();

        $appsInput = $request->input('apps', []); 

        // 3. จัดเตรียมข้อมูลให้อยู่ใน Format ที่ฟังก์ชัน sync() ต้องการ
        $syncData = [];
        foreach ($appsInput as $appId => $level) {
            if ($level != '0' && $level != '') {
                $syncData[$appId] = ['acclevel' => $level];
            } else {
                // ถ้าเลือกเป็น '0' หรือค่าว่าง ให้ลบเฉพาะแอปนี้ (Optional)
                $group->apps()->detach($appId);
            }
        }
        $group->apps()->syncWithoutDetaching($syncData);

        return redirect()->route('group.det',$group->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
        
    }

    // Function สำหรับในการเปลี่ยนสถานะข้อมูลใน ตาราง usergroup ในเป็น Active หรือ In Active 
    public function del($id) {
        $group = UserGroup::find($id); 

        if (!$group) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลผู้ใช้งานที่ต้องการแก้ไข');
        }

        $group->status = $group->status == 1 ? 0 : 1;
        $group->save();
        $message = $group->status == 1 ? 'เปลี่ยนสถานะเป็น Active เรียบร้อย' : 'เปลี่ยนสถานะเป็น Inactive เรียบร้อย';
        return redirect()->back()->with('success', $message);
    }
}
