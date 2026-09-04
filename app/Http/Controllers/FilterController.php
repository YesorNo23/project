<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filter;
use App\Models\FilterDetail;

class FilterController extends Controller
{
    // Function สำหรับนำข้อมูลใน ตาราง category ไปแสดงผลที่หน้า ให้กับ Admin
    public function def(){
        $filters = Filter::all();
        return view('admin.filter_def',compact('filters'));
    }

     // Function สำหรับเรียกข้อมูล ตาราง category ตาม id มาเตรียมแก้ไขข้อมูล
    public function edit($id = null){
        $filter = Filter::find($id);
        return view('admin.filter_edit',compact('filter'));
        
    }

    // Function สำหรับในการแสดงรายละเอียดข้อมูลใน ตาราง usergroup ตาม id ที่ส่งมา
    public function det($id){
        $filter = Filter::find($id);
        if($filter){
            return view('admin.filter_det',compact('filter'));
        }
        else{
            return redirect()->route('filter.def');
        }
    }

     // Function สำหรับการบันทึกข้อมูลลงใน ตาราง category และ category_detail
    public function save(Request $request ,$id = null) 
    {
        // การกำหนดรูปแบบข้อมูลที่กรอก 
        $request->validate([
        'name'            => 'required|max:50',
        'filter_values.*' => 'distinct|max:50'

    ], [
        // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
        'name.required'            => 'กรุณากรอกประเภทตัวกรอกด้วยครับ',
        'name.max'                 => 'กรอกไม่เกิน 50 ตัวอักษร',
        'filter_values.*.distinct' => 'มีค่าที่ซ้ำกันในรายการข้อมูล',
        'filter_values.*.max'      => 'กรอกไม่เกิน 50 ตัวอักษร',
        
    ]);

        // การบันทึกข้อมูลลงในตาราง category (โดยที่ id เท่ากับค่า null จะเป็นการเพิ่มข้อมูล แต่ถ้าไม่ใช่จะเป็นการอัพเดตข้อมูล ตามหมายเลข id)
        $filter = Filter::findOrNew($id);
        $filter->name = $request->name;
        $filter->save();
        if(isset($filter->value)){
            $filter->value()->where('catagory_id','=',$filter->id)->update(['status' => 0]);
        }
        
        // การบันทึกข้อมูลลงในตาราง category_detail
        $existingIds = $filter->value->pluck('id')->toArray();

        foreach($request->filter_values as $index => $value) {
            if(!$value) continue;

            $data = [
                'catagory_id' => $filter->id,
                'name'        => $value,
                'status'      => 1
            ];

            if(isset($existingIds[$index])) {
                $filter->value()->where('id', $existingIds[$index])->update($data);
            } else {
                $filter->value()->create($data);
            }
        }
        return redirect()->route('filter.det',$filter->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
    }

    // Function สำหรับในการเปลี่ยนสถานะข้อมูลใน ตาราง category ในเป็น Active หรือ In Active 
    public function del($id) {
        $filter = Filter::find($id); 

        if (!$filter) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลตัวกรองที่ต้องการแก้ไข');
        }

        $filter->status = $filter->status == 1 ? 0 : 1;
        $filter->save();
        $message = $filter->status == 1 ? 'เปลี่ยนสถานะเป็น Active เรียบร้อย' : 'เปลี่ยนสถานะเป็น Inactive เรียบร้อย';
        return redirect()->back()->with('success', $message);
    }
}
