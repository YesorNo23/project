<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\Filter;
use App\Models\CatMusic;


class MusicController extends Controller
{
    // Function สำหรับนำข้อมูลใน ตาราง music(คลื่นเสียง) ไปแสดงผลที่หน้า ให้กับ Admin
    public function def(){
        $musics = Music::all();
        return view('admin.music_def' , compact('musics'));
    }

    // Function สำหรับในการแสดงรายละเอียดข้อมูลใน ตาราง music(คลื่นเสียง) ตาม id ที่ส่งมา
    public function det($id){
        $music = Music::find($id);
        if(isset($music)){
            return view('admin.music_det', compact('music'));
        }
        else{
            return redirect()->back();
        }
    }

    // Function สำหรับเรียกข้อมูล ตาราง music(คลื่นเสียง) ตาม id มาเตรียมแก้ไขข้อมูล
    public function edit($id = null){
        $music = Music::find($id);
        $filters = Filter::all()->where('status','=',1);
        return view('admin.music_edit',compact('music','filters'));
        
    }

    // Function สำหรับการบันทึกข้อมูลลงใน ตาราง music(คลื่นเสียง)
    public function save(Request $request ,$id = null ){

        // การกำหนดรูปแบบข้อมูลที่กรอก 
        $request->validate([
        'name'      => 'required|max:255|', 
        'detail'    =>'',
        'image'     =>'extensions:jpg,png,webp',
        'file_path' => ($id ? '' : 'required|') . 'mimes:mp3,wav,aac,ogg|max:122880' 

    ], [
        // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
        'name.required'      => 'กรุณากรอกชื่อคลื่นเสียงด้วยครับ',
        'name.max'           => 'กรอกชื่อคลื่นเสียงไม่เกิน 255 ตัวอักษร',
        'image.extensions'   => 'ไฟล์ที่อนุญาต jpg png webp',
        'file_path.required' => 'กรุณาอัปโหลดไฟล์เสียงด้วยครับ',
        'file_path.mimes'    => 'ไฟล์เสียงที่อนุญาต mp3 wav aac ogg',
        'file_path.max'      => 'ไฟล์เสียงมีขนาดใหญ๋เกินไป (120 MB)',
    ]);
        
        // การบันทึกข้อมูลลงในตาราง music (โดยที่ id เท่ากับค่า null จะเป็นการเพิ่มข้อมูล แต่ถ้าไม่ใช่จะเป็นการอัพเดตข้อมูล ตามหมายเลข id)
        $music = Music::findOrNew($id);
        $music->name = $request->name;
        $music->detail = $request->detail;
        $music->status = $request->status ?? 1;

        // เข้ารหัสชื่อไฟล์รูปภาพ และ บันทึกลงในโฟลเดอร์ public/image และ บันทึกชื่อไฟล์รูป ลงในตาราง music(image)
        if ($request->hasFile('image')) {
            $imageName = time() . '_img.' . $request->image->extension();
            $request->image->move(public_path('image'), $imageName);
            $music->image = $imageName;
        }

        // เข้ารหัสชื่อไฟล์เสียง บันทึกลงในโฟลเดอร์ public/audio และ บันทึกชื่อไฟล์เสียง ลงในตาราง music(file_path)
        if ($request->hasFile('file_path')) {
            $audioName = time() . '_audio.' . $request->file_path->extension();
            $request->file_path->move(public_path('audio'), $audioName);
            $music->file_path = $audioName;

            // คำนวญความยาวของไฟล์เสียง เพื่อบันทึกลงในตาราง music(duration)
            $getID3 = new \getID3;
            $fileInfo = $getID3->analyze(public_path('audio/' . $audioName));
            if (isset($fileInfo['playtime_seconds'])) {
                $seconds = $fileInfo['playtime_seconds'];
                $music->duration = $seconds; 
            }
        }

        $music->save();
        $action = $music->wasRecentlyCreated ? 'เพิ่ม' : 'แก้ไข';

        $this->saveLog("{$action}คลื่นเสียงที่ {$music->id}","จัดการคลื่นเสียง");

        // บันทึกประเภทของคลื่นเสียง ลงในตาราง cat_music โดยรับ id ของ ตาราง cat_detail จาก checkbox
        $types = $request->types ?? [];
        $syncData = [];
        foreach ($types as $id) {
            $syncData[$id] = ['status' => 1]; 
        }
        $music->filterDetails()->sync($syncData);// คำสั่งบันทึก
        
        return redirect()->route('music.det', $music->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
    }

    // Function สำหรับในการเปลี่ยนสถานะข้อมูลใน ตาราง music(คลื่นเสียง) ในเป็น Active หรือ In Active 
    public function del($id) {
        $music = Music::find($id); 

        if (!$music) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลผู้ใช้งานที่ต้องการแก้ไข');
        }

        $music->status = $music->status == 1 ? 0 : 1;
        $music->save();
        $action = $music->status ==1 ? 'Active' : 'In Active';

        $this->saveLog("{$action} คลื่นเสียงที่ {$music->id}","จัดการคลื่นเสียง");
        $message = $music->status == 1 ? 'เปลี่ยนสถานะเป็น Active เรียบร้อย' : 'เปลี่ยนสถานะเป็น Inactive เรียบร้อย';
        return redirect()->back()->with('success', $message);
    }


    public function get_songs($category=null)
    {
        $catlist = ['focus'=>'เพิ่มสมาธิและโฟกัส',
                    'mood'=>'ปรับอารมณ์ให้ดีขึ้น',
                    'relax'=>'ผ่อนคลายทั่วไป',
                    'sleep'=>'นอนหลับ',
                    'stress'=>'ลดความเครียด'];

        $songs = Music::with(['filterDetails' => function ($q) use ($category) {
            $q->where('catagory_id', 2)
            ->when($category, function ($query, $category) {
              $query->where('name', $catlist[$category]);
            });
        }])->get();

        $grouped = [];
        foreach ($songs as $song) {
            foreach ($song->filterDetails as $filter) {
                $grouped[$filter->name][] = [
                    'id'        => $song->id,
                    'musicname' => $song->name,
                    'image'     => $song->image,
                    'musicfile' =>$song->file_path,
                    'cat' =>$filter->name,
                ];
            }
        }

        return response()->json($grouped);
    }
}
