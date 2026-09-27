<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlayList;
use App\Models\PlayListDetail;
use App\Models\Music;
use Illuminate\Support\Facades\DB;

class PlayListController extends Controller
{
    //
    public function def(){
        $playLists = PlayList::all();
        return view('admin.playlist_def' , compact('playLists'));
    }

    public function det($id){
        $playlist = PlayList::find($id);
        if(isset($playlist)){
            return view('admin.playlist_det', compact('playlist'));
        }
        else{
            return redirect()->back();
        }
    }

    public function edit($id = null){
        $playlist = PlayList::find($id);
        return view('admin.playlist_edit',compact('playlist'));
        
    }

    public function save(Request $request ,$id = null ){

        // การกำหนดรูปแบบข้อมูลที่กรอก 
        $request->validate([
        'name'      => 'required|max:255|', 
        'detail'    => '',
        'image'     => 'extensions:jpg,png,webp',
        'status'    => 'required'

    ], [
        // การแจ้งเตือนเมื่อข้อมูลที่กรอกไม่ตรงตามรูปแบบ
        'name.required'    => 'กรุณากรอกชื่อเพลย์ลิสต์ด้วยครับ',
        'name.max'         => 'กรอกชื่อเพลย์ลิสต์ไม่เกิน 255 ตัวอักษร',
        'image.extensions' => 'ไฟล์ที่อนุญาต jpg png webp',
        'status.required'  => 'กรุณาเลือกสถานะเพลย์ลิสต์ด้วยครับ'
    ]);
        
        // การบันทึกข้อมูลลงในตาราง music (โดยที่ id เท่ากับค่า null จะเป็นการเพิ่มข้อมูล แต่ถ้าไม่ใช่จะเป็นการอัพเดตข้อมูล ตามหมายเลข id)
        $playlist = PlayList::findOrNew($id);
        $playlist->uid = auth()->id();
        $playlist->name = $request->name;
        $playlist->detail = $request->detail;
        $playlist->status = $request->status;

        // เข้ารหัสชื่อไฟล์รูปภาพ และ บันทึกลงในโฟลเดอร์ public/image และ บันทึกชื่อไฟล์รูป ลงในตาราง music(image)
        if ($request->hasFile('image')) {
            $imageName = time() . '_img.' . $request->image->extension();
            $request->image->move(public_path('image'), $imageName);
            $playlist->image = $imageName;
        }

        $playlist->save();
        $action = $playlist->wasRecentlyCreated ? 'เพิ่ม' : 'แก้ไข';

        $this->saveLog("{$action}เพลย์ลิสต์ที่ {$playlist->id}","จัดการคลื่นเสียง");
        
        return redirect()->route('playlist.det', $playlist->id)->with('success', 'บันทึกข้อมูลเรียบร้อย!');
    
    }

    public function manage($id = null){
        $playlist = PlayList::find($id);
       
        $musics = Music::all();
        return view('admin.playlist_manage' , compact('playlist','musics'));
    }

    public function update(Request $request, $id)
    {
        // 1. ค้นหาเพลย์ลิสต์ที่ต้องการจัดการ
        $playlist = PlayList::findOrFail($id);

        // 2. ใช้ DB::transaction เพื่อความปลอดภัย (ถ้าบันทึกพังกลางคัน ระบบจะย้อนกลับให้)
        DB::transaction(function () use ($request, $playlist) {
            
            // ลบข้อมูลเพลงเก่าทั้งหมดในเพลย์ลิสต์นี้ออกก่อนเพื่อเตรียมบันทึกใหม่
            if(count($playlist->music) > 0){
                $playlist->details()->delete();
            }

            // ตรวจสอบว่าหน้าบ้านมีการส่งเพลงมาหรือไม่ (กรณีที่เคลียร์เพลงออกจนหมด)
            if ($request->has('music_ids') && is_array($request->music_ids)) {
                
                $prepareData = [];
                
                // วนลูปเพื่อเก็บข้อมูล โดยใช้ $index + 1 เป็นตัวระบุลำดับ (order)
                foreach ($request->music_ids as $index => $musicId) {
                    $prepareData[] = [
                        'playlistid'     => $playlist->id,
                        'musicid'        => $musicId,
                        'playlist_order' => $index + 1, // บันทึกลำดับเริ่มจาก 1, 2, 3... ตามที่ลากวางจริง
                        'add_date'       => now(),
                        
                    ];
                }

                // บันทึกข้อมูลทั้งหมดลงตาราง playlist_detail ในครั้งเดียว (Bulk Insert) เพื่อความรวดเร็ว
                PlaylistDetail::insert($prepareData);
                $action = "แก้ไขคลื่นเสียง";

                $this->saveLog("{$action}เพลย์ลิสต์ที่ {$playlist->id}","จัดการคลื่นเสียง");
            }
        });

        // 3. ส่งกลับไปยังหน้าเดิมพร้อมข้อความแจ้งเตือนสำเร็จ
        return redirect()->route('playlist.det',$id)->with('success', 'บันทึกการจัดลำดับเพลย์ลิสต์เรียบร้อยแล้ว!');
    }

    public function del($id) {
        $playlist = PlayList::find($id); 

        if (empty($playlist)) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลที่ต้องการแก้ไข');
        }

        $playlist->status = $playlist->status > 0 ? 0 : 1;
        $playlist->save();
        $message = $playlist->status > 0 ? 'เปลี่ยนสถานะเป็น Private เรียบร้อย' : 'เปลี่ยนสถานะเป็น In Active เรียบร้อย';
        return redirect()->back()->with('success', $message);
    }

    public function get_playlists(Request $request)
    {
        $userId = auth()->id();

        $playlists = Playlist::where('uid', $userId)
            ->where('status','>',0)
            ->withCount('music')
            ->with(['music' => function ($q) {
                $q->limit(4); // เอาแค่ 4 เพลงแรกไปทำปก collage
            }])
            ->orderByDesc('created_at')
            ->get();

        $result = $playlists->map(function ($playlist) {
            return [
                'id'         => $playlist->id,
                'name'       => $playlist->name,
                'cover' => optional($playlist->music->first())->image,
                'song_count' => $playlist->music_count,
                'songs'      => $playlist->music->map(function ($music) {   // ← แก้ songs → music
                    return [
                        'id'    => $music->id,
                        'image' => $music->image,
                    ];
                })->values(),
            ];
        });

        return response()->json($result);
    }

   
    public function get_playlist(Request $request, $id)
    {
        $userId = auth()->id();

        $playlist = Playlist::where('uid', $userId)
            ->where('id', $id)
            ->with(['music' => function ($query) {
                $query->where('music.status', '>',0);}])
            ->firstOrFail();

        $result = [
            'id'          => $playlist->id,
            'name'        => $playlist->name,
            'description' => $playlist->detail,
            'cover'       => optional($playlist->music->first())->image,
            'songs'       => $playlist->music->map(function ($music) {
                return [
                    'id'         => $music->id,
                    'musicname'  => $music->name,
                    'musicfile'  => $music->file_path,
                    'image'      => $music->image,
                    'duration'   => $music->duration,
                ];
            })->values(),
        ];

        return response()->json($result);
    }


   public function update_playlist(Request $request, $id = null)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'song_ids'    => 'nullable|array',
            'song_ids.*'  => 'integer|exists:music,id',
        ]);

        // หาเพลย์ลิสต์เดิมของ User รายนี้ (ถ้าระบุ $id มา)
        $playlist = $id ? Playlist::where('id', $id)->where('uid', $userId)->first() : null;

        if ($playlist) {
            // กรณีมีข้อมูลเดิม -> ทำการ Update
            $playlist->update([
                'name'   => $validated['name'],
                'detail' => $validated['description'] ?? null,
            ]);
        } else {
            // กรณีหาไม่เจอ หรือ $id เป็น null -> ทำการ Create ใหม่
            $playlist = Playlist::create([
                'uid'    => $userId,
                'name'   => $validated['name'],
                'detail' => $validated['description'] ?? null,
                'status' => '1',
            ]);
        }

        // ซิงค์ข้อมูลเพลง (แก้ปัญหา sync เมื่อรับค่า array เปล่า)
        if ($request->has('song_ids')) {
            $playlist->music()->sync($validated['song_ids'] ?? []);
        }

        return response()->json([
            'message'  => 'บันทึกเพลย์ลิสต์สำเร็จ',
            'playlist' => [
                'id'          => $playlist->id,
                'name'        => $playlist->name,
                'description' => $playlist->detail,
            ],
        ]);
    }

  
    public function add_songs_to_playlist(Request $request, $id)
    {
        $userId = auth()->id();

        $playlist = Playlist::where('uid', $userId)
            ->where('id', $id)
            ->firstOrFail(); // เจ้าของเพลย์ลิสต์เท่านั้นที่เพิ่มเพลงได้

        $validated = $request->validate([
            'song_ids'   => 'required|array|min:1',
            'song_ids.*' => 'integer|exists:music,id',
        ]);

        // syncWithoutDetaching() = เพิ่มเพลงใหม่เข้าไป โดยไม่ลบเพลงเดิมที่มีอยู่แล้วออก
        // และไม่ทำให้เพลงซ้ำ ถ้า id ไหนอยู่ในเพลย์ลิสต์แล้วจะข้ามไปเฉยๆ
        $playlist->music()->syncWithoutDetaching($validated['song_ids']);

        return response()->json([
            'message'    => 'เพิ่มเพลงเข้าเพลย์ลิสต์สำเร็จ',
            'song_count' => $playlist->music()->count(),
        ]);
    }

    public function delete_playlist(Request $request, $id)
    {
        $userId = auth()->id();

        $playlist = Playlist::where('uid', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $playlist->update([
            'status' => 0
        ]);

        return response()->json([
            'message'  => 'ลบเพลย์ลิสต์สำเร็จ',
            'playlist' => [
                'id'          => $playlist->id,
                'name'        => $playlist->name,
                'description' => $playlist->detail,
            ],
        ]);
    }

}
