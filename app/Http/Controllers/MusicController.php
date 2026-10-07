<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\Filter;
use App\Services\B2Client;
use Illuminate\Support\Str;

class MusicController extends Controller
{
    // แสดงรายการคลื่นเสียงทั้งหมด
    public function def(){
        $musics = Music::all();
        return view('admin.music_def', compact('musics'));
    }

    // แสดงรายละเอียดคลื่นเสียงตาม id
    public function det($id){
        $music = Music::find($id);
        if ($music) {
            return view('admin.music_det', compact('music'));
        }
        return redirect()->back()->with('error', 'ไม่พบข้อมูลคลื่นเสียงที่ต้องการ');
    }

    // ดึงข้อมูลเตรียมแก้ไข
    public function edit($id = null){
        $music = Music::find($id);
        $filters = Filter::where('status', 1)->get(); // กรองที่ระดับ Query
        return view('admin.music_edit', compact('music', 'filters'));
    }

    // บันทึกหรืออัปเดตข้อมูล
    public function save(Request $request, $id = null){

        $request->validate([
            'name'      => 'required|max:255', 
            'detail'    => 'nullable',
            'image'     => 'nullable|mimes:jpg,png,webp|max:10240',
            'file_path' => ($id ? 'nullable|' : 'required|') . 'mimes:mp3,wav,aac,ogg|max:122880' 
        ], [
            'name.required'      => 'กรุณากรอกชื่อคลื่นเสียงด้วยครับ',
            'name.max'           => 'กรอกชื่อคลื่นเสียงไม่เกิน 255 ตัวอักษร',
            'image.mimes'        => 'ไฟล์รูปภาพที่อนุญาต: jpg, png, webp',
            'file_path.required' => 'กรุณาอัปโหลดไฟล์เสียงด้วยครับ',
            'file_path.mimes'    => 'ไฟล์เสียงที่อนุญาต: mp3, wav, aac, ogg',
            'file_path.max'      => 'ไฟล์เสียงมีขนาดใหญ่เกินไป (สูงสุด 120 MB)',
        ]);
        
        $music = Music::findOrNew($id);
        $music->name = $request->name;
        $music->detail = $request->detail;
        $music->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            $imageName = time() . '_img.' . $request->image->extension();
            $request->image->move(public_path('image'), $imageName);
            $music->image = $imageName;
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $ext  = strtolower($file->extension());

            // 1) อ่านความยาวเพลงจากไฟล์ชั่วคราว (ก่อนอัปโหลด)
            if (class_exists('\getID3')) {
                $getID3   = new \getID3;
                $fileInfo = $getID3->analyze($file->getRealPath());
                if (isset($fileInfo['playtime_seconds'])) {
                    $music->duration = $fileInfo['playtime_seconds'];
                }
            }

            // 2) อัปโหลดขึ้น B2
            $audioName =  time() . '_audio.' . $ext;

            $result = app(B2Client::class)->uploadFile(
                $file->getRealPath(),
                $audioName,
                $file->getMimeType()
            );

            // 3) เก็บชื่อไฟล์ใน B2 ลง DB
            $music->file_path = $result['fileName']; // เช่น audio/1759800000_Ab12Cd34_audio.mp3
        }

        $music->save();
        $action = $music->wasRecentlyCreated ? 'เพิ่ม' : 'แก้ไข';

        if (method_exists($this, 'saveLog')) {
            $this->saveLog("{$action}คลื่นเสียงที่ {$music->id}", "จัดการคลื่นเสียง");
        }

        $types = $request->types ?? [];
        $syncData = [];
        foreach ($types as $typeId) {
            $syncData[$typeId] = ['status' => 1]; 
        }
        $music->filterDetails()->sync($syncData);
        
        return redirect()->route('music.det', $music->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
    }

    // เปลี่ยนสถานะ Active / Inactive
    public function del($id) {
        $music = Music::find($id); 

        if (!$music) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลคลื่นเสียงที่ต้องการแก้ไข');
        }

        $music->status = $music->status == 1 ? 0 : 1;
        $music->save();
        
        $action = $music->status == 1 ? 'Active' : 'In Active';

        if (method_exists($this, 'saveLog')) {
            $this->saveLog("{$action} คลื่นเสียงที่ {$music->id}", "จัดการคลื่นเสียง");
        }

        $message = $music->status == 1 ? 'เปลี่ยนสถานะเป็น Active เรียบร้อย' : 'เปลี่ยนสถานะเป็น Inactive เรียบร้อย';
        return redirect()->back()->with('success', $message);
    }

    // API ดึงเพลงแยกตามหมวดหมู่
    public function get_songs($category = null)
    {
        $catlist = [
            'focus'  => 'เพิ่มสมาธิและโฟกัส',
            'mood'   => 'ปรับอารมณ์ให้ดีขึ้น',
            'relax'  => 'ผ่อนคลายทั่วไป',
            'sleep'  => 'นอนหลับ',
            'stress' => 'ลดความเครียด'
        ];

        $targetCatName = ($category && isset($catlist[$category])) ? $catlist[$category] : null;

        // ดึงเฉพาะเพลงที่มี Relationship ตรงตามเงื่อนไข
        $query = Music::query();

        if ($targetCatName) {
            $query->whereHas('filterDetails', function ($q) use ($targetCatName) {
                $q->where('category_id', 2)
                  ->where('name', $targetCatName);
            });
        }

        $songs = $query->with(['filterDetails' => function ($q) use ($targetCatName) {
            $q->where('category_id', 2);
            if ($targetCatName) {
                $q->where('name', $targetCatName);
            }
        }])->where('status','>',0)->get();

        $grouped = [];
        foreach ($songs as $song) {
            foreach ($song->filterDetails as $filter) {
                $grouped[$filter->name][] = [
                    'id'        => $song->id,
                    'musicname' => $song->name,
                    'image'     => $song->image,
                    'musicfile' => $song->file_path,
                    'cat'       => $filter->name,
                ];
            }
        }

        return response()->json($grouped);
    }
}