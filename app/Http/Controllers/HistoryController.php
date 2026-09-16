<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Music;
use Carbon\Carbon;


class HistoryController extends Controller
{
    //
    public function def(){
        $histories = History::all();
        return view('admin.history_def',compact('histories'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'musicid'       => 'required|integer|exists:music,id',
            'play_duration' => 'required|integer|min:1',
        ]);

        History::create([
            'uid'           => auth()->id(), // เป็น null ได้ถ้า guest ฟังได้ (เช็คว่า column เป็น nullable ไหม)
            'musicid'       => $validated['musicid'],
            'play_duration' => $validated['play_duration'],
            'created_at'=>now(),
            'status'        => 1,
        ]);

        Music::where('id', $validated['musicid'])->increment('play_count');

        return response()->json(['status' => 'ok']);
    }


    public function get_history(Request $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 15);

        // 1. นับจำนวนเพลงที่ฟังในเดือนนี้
        $totalThisMonth = History::where('uid', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // 2. ดึงข้อมูล History พร้อม Eager Load Relation แบบ ซ้อน Relation (music -> filterDetails)
        $histories = History::where('uid', $userId)
            ->with(['music.filterDetails' => function ($q) {
                $q->where('category_id', 2);
            }])
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();

        // 3. จัดกลุ่มตามวันที่เปิดฟัง (วันนี้, เมื่อวาน, วันที่)
        $groupedByDate = $histories->groupBy(function ($item) {
            if (!$item->created_at) return 'รายการย้อนหลัง';
            $date = Carbon::parse($item->created_at);
            if ($date->isToday()) return 'วันนี้';
            if ($date->isYesterday()) return 'เมื่อวาน';
            return $date->format('d/m/Y');
        });

        // 4. แปลงโครงสร้างให้ตรงกับที่ JavaScript ฝั่งหน้าบ้านต้องการ
        $groups = [];
        foreach ($groupedByDate as $dateLabel => $items) {
            $groupItems = [];
            foreach ($items as $item) {
                $music = $item->music;
                if (!$music) continue; // ข้ามหากไม่มีข้อมูลเพลง

                // ดึงชื่อหมวดหมู่จาก filterDetails (ถ้ามี)
                $filter = $music->filterDetails->first();
                $catName = $filter ? $filter->name : '';

                $groupItems[] = [
                    'id'        => $music->id,
                    'musicname' => $music->name,      // ปรับตาม Field จริง: $music->name
                    'image'     => $music->image,     // ปรับตาม Field จริง: $music->image
                    'musicfile' => $music->file_path, // ปรับตาม Field จริง: $music->file_path
                    'cat'       => $catName,          // ดึงจาก filterDetails->name
                    'time'      => $item->created_at ? Carbon::parse($item->created_at)->format('H:i') : '--:--',
                ];
            }

            if (!empty($groupItems)) {
                $groups[] = [
                    'label' => $dateLabel,
                    'items' => $groupItems,
                ];
            }
        }

        // 5. ตรวจสอบว่ายังมีข้อมูลให้กด Load More ต่อหรือไม่
        $totalAll = History::where('uid', $userId)->count();
        $hasMore = ($offset + $histories->count()) < $totalAll;

        return response()->json([
            'total'   => $totalThisMonth,
            'groups'  => $groups,
            'hasMore' => $hasMore,
        ]);

    }
}
