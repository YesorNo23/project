<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Auth\Events\Registered; // 1. นำเข้า Event สำหรับส่งอีเมล
use Illuminate\Support\Facades\Auth;   // 2. นำเข้า Auth สำหรับจับล็อกอิน

class UserController extends Controller
{
    // Function สำหรับนำข้อมูลใน ตาราง user ไปแสดงผลที่หน้า ให้กับ Admin
    public function def(){
        $users = User::all();
        return view('admin.users_def',compact('users'));
    }

    // Function สำหรับการบันทึกข้อมูลลงใน ตาราง user โดยรับข้อมูลมาจาก Register From usere edit form
    public function save(Request $request ,$id = null ) 
    {
        // การกำหนดรูปแบบข้อมูลที่กรอก 
        $request->validate([
        'email'    => 'required|email|unique:user,email,' . ($id ?? 'NULL'),
        'password' => $id ? 'sometimes|prohibited' : 'required|min:8',
        'name'     => 'required|between:1,255',
        'surname'  => 'required|between:1,255',
        'birthdate'=> 'required',
        'gender'   => 'required',

    ], [
        // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
        'email.required'     => 'กรุณากรอก email ด้วยครับ',
        'email.email'        => 'กรุณากรอกในรูปแบบ email ด้วยครับ',
        'email.unique'       => 'email นี้มีผู้ใช้แล้ว',
        'password.required'  => 'ต้องตั้งรหัสผ่านอย่างน้อย 8 ตัวอักษร',
        'name.required'      => 'กรุณากรอกชื่อด้วยครับ',
        'name.between'       => 'กรอกไม่เกิน 255 ตัวอักษร',
        'surname.required'   => 'กรุณากรอกนามสกุลด้วยครับ',
        'surname.between'    => 'กรอกไม่เกิน 255 ตัวอักษร',
        'birthdate.required' => 'กรุณาระบุวันเกิดด้วยครับ',
        'gender.required'    => 'กรุณาระบุเพศด้วยครับ',
    ]);
        // การบันทึกข้อมูลลงตาราง user (โดยที่ id เท่ากับค่า null จะเป็นการเพิ่มข้อมูล แต่ถ้าไม่ใช่จะเป็นการอัพเดตข้อมูล ตามหมายเลข id)
        $user = User::findOrNew($id);
        $user->name      = $request->name;
        $user->surname   = $request->surname;
        $user->email     = $request->email;
        $user->birthdate = $request->birthdate;
        $user->gender    = $request->gender;
        $user->status    = $request->status ?? 1; 
        $user->usertype  = \App\Models\UserGroup::find($request->group)->name ; // บันทีกชื่อประเภทกลุ่มผู้ใช้
        $user->save();
        
        // บันทึก id ของผู้ใช้ ในตาราง uig (user in group) และ id กลุ่มผู้ใช้(ugid)
        $user->uig()->updateOrCreate(['uid'    => $user->id], 
                                     ['ugid'   => $request->group ?? 2],
                                     ['status' => 1]);
                
        // เมื่อเป็นการสมัครหรือทำการเพิ่มผู้ใช้ จะให้กรอก password และทำการเข้ารหัส 
        if($request->password){
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect()->route('user.det',$user->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
        }

        return redirect()->route('user.det', $user->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
    }

    // Function สำหรับในการเปลี่ยนสถานะข้อมูลใน ตาราง user ในเป็น Active หรือ In Active 
    public function del($id) {
        $user = User::find($id); 

        if (!$user) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลผู้ใช้งานที่ต้องการแก้ไข');
        }

        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();
        $message = $user->status == 1 ? 'เปลี่ยนสถานะเป็น Active เรียบร้อย' : 'เปลี่ยนสถานะเป็น Inactive เรียบร้อย';
        return redirect()->back()->with('success', $message);
    }

    // Function สำหรับในการแสดงรายละเอียดข้อมูลใน ตาราง user ตาม id ที่ส่งมา
    public function det($id){
        $user = User::find($id);
        if($user){
            return view('admin.user_det', compact('user'));
        }
        else{
            return redirect()->back();
        }
    }
    
    // Function ในการเรียกข้อมูลเพื่อทำการเตรียมแก้ไขข้อมูลของผู้ใช้ มี 2 รูปแบบ คือ 1.เพิ่มผู้ใช้(มี password ให้กรอก) 2.แก้ไขข้อมูลใช้(ไม่มี)
    public function editinfo($id = null){
        $user = User::find($id);
        $groups = UserGroup::all()->where('status','=',1);
        return view('admin.user_editinfo', compact('user','groups'));
    }

    public function editpass($id){
        $user = User::find($id);
        $userId = $user->id;
        return view('admin.user_editpass', compact('userId'));
    }

    public function savepass(Request $request , $id){

        $request->validate([
        'old_password' => 'required|current_password',
        'new_password' => 'required|min:8|confirmed',
        

    ], [
        'old_password.required' => 'กรุณากรอกรหัสผ่านเก่า',
        'old_password.current_password' => 'รหัสผ่านเก่าที่คุณกรอกไม่ถูกต้อง!',
        'new_password.required' => 'กรุณากรอกรหัสผ่านใหม่',
        'new_password.min' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
        'new_password.confirmed' => 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
    ]);

        $user = User::findOrNew($id);
        $user->password = bcrypt($request->new_password);
        $user->save();
        return redirect()->route('user.det',$user->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
    }

    // Function สำหรับการบันทึกข้อมูลลงใน ตาราง user โดยรับข้อมูลมาจาก Register From usere edit form
    public function register(Request $request ) 
    {
        // การกำหนดรูปแบบข้อมูลที่กรอก 
        $request->validate([
        'email'    => 'required|email|unique:user,email',
        'password' => 'required|min:8',
        'name'     => 'required|between:1,100',
        'surname'  => 'required|between:1,100',
        'birthdate'=> 'required',
        'gender'   => 'required',

    ], [
        // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
        'email.required'     => 'กรุณากรอก email ด้วยครับ',
        'email.email'        => 'กรุณากรอกในรูปแบบ email ด้วยครับ',
        'email.unique'       => 'email นี้มีผู้ใช้แล้ว',
        'password.required'  => 'ต้องตั้งรหัสผ่านอย่างน้อย 8 ตัวอักษร',
        'name.required'      => 'กรุณากรอกชื่อด้วยครับ',
        'name.between'       => 'กรอกไม่เกิน 100 ตัวอักษร',
        'surname.required'   => 'กรุณากรอกนามสกุลด้วยครับ',
        'surname.between'    => 'กรอกไม่เกิน 100 ตัวอักษร',
        'birthdate.required' => 'กรุณาระบุวันเกิดด้วยครับ',
        'gender.required'    => 'กรุณาระบุเพศด้วยครับ',
    ]);
        // การบันทึกข้อมูลลงตาราง user (โดยที่ id เท่ากับค่า null จะเป็นการเพิ่มข้อมูล แต่ถ้าไม่ใช่จะเป็นการอัพเดตข้อมูล ตามหมายเลข id)
        $user = User::create([
            'name'=>$request->name,
            'surname'=>$request->surname,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'birthdate'=>$request->birthdate,
            'gender'=>$request->gender,
            'status'=>1,
            'usertype'=>\App\Models\UserGroup::find(2)->name ,
        ]);
        
        // บันทึก id ของผู้ใช้ ในตาราง uig (user in group) และ id กลุ่มผู้ใช้(ugid)
        $user->uig()->create(['uid'    => $user->id], 
                             ['ugid'   => '2'],
                             ['status' => '1']);

        $this->saveLog('ผูใช้ uid: '.$user->id.' ลงทะเบียน','ลงทะเบียน');

        return redirect()->route('regisfrom')->with('success', 'บันทึกข้อมูลเรียบร้อย!');
    }

    // Function สำหรับการบันทึกข้อมูลลงใน ตาราง user โดยรับข้อมูลมาจาก Register From usere edit form
    public function updateprofile(Request $request)
    {
        // การกำหนดรูปแบบข้อมูลที่กรอก
        $validated = $request->validate([
            'name'      => 'required|between:1,100',
            'surname'   => 'required|between:1,100',
            'birthdate' => 'required|date',
            'gender'    => 'required',
        ], [
            // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
            'name.required'      => 'กรุณากรอกชื่อด้วยครับ',
            'name.between'       => 'กรอกไม่เกิน 100 ตัวอักษร',
            'surname.required'   => 'กรุณากรอกนามสกุลด้วยครับ',
            'surname.between'    => 'กรอกไม่เกิน 100 ตัวอักษร',
            'birthdate.required' => 'กรุณาระบุวันเกิดด้วยครับ',
            'birthdate.date'     => 'รูปแบบวันเกิดไม่ถูกต้อง',
            'gender.required'    => 'กรุณาระบุเพศด้วยครับ',
            'gender.in'          => 'ค่าที่เลือกไม่ถูกต้อง',
        ]);

        $user = User::findOrFail(auth()->id());
        $user->update($validated);
        $this->saveLog('แก้ไขข้อมูลส่วนตัว','แก้ไขข้อมูล');

        return response()->json([
            'success' => true,
            'message' => 'บันทึกข้อมูลเรียบร้อย!',
            'data'    => $user,
        ], 200);
    }

    public function changepassword(Request $request){

        $request->validate([
        'old_password' => 'required|current_password',
        'new_password' => 'required|min:8|confirmed',
        
    ], [
        'old_password.required' => 'กรุณากรอกรหัสผ่านเก่า',
        'old_password.current_password' => 'รหัสผ่านเก่าที่คุณกรอกไม่ถูกต้อง!',
        'new_password.required' => 'กรุณากรอกรหัสผ่านใหม่',
        'new_password.min' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
        'new_password.confirmed' => 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
    ]);

        $user = User::findOrFail(auth()->id());
        $user->password = bcrypt($request->new_password);
        $user->save();
        $this->saveLog('เปลี่ยนรหัสผ่าน','แก้ไขข้อมูล');

        
    }



}

