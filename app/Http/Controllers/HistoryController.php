<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\History;
use App\Models\Music;

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
}
