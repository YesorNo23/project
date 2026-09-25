<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FilterDetail;
use App\Models\Music;
use App\Models\History;
use App\Models\Assessment;


class HomePageController extends Controller
{

    // function แสดงหน้า Dashbord ของ admin
    public function adminDashbord()
    {
        // ยอดการฟังคลื่นเสียงทั้งหมดของเว็ป
        $totalListens = number_format(MUsic::sum('play_count'));

        // จำนวนผู้ใช้ทั้งหมด
        $userNum = number_format(User::count());

        // ข้อมูลการฟังคลื่นเสียง และจำนวนครั้งการฟังรายวัน
        $dailyListens =  History::whereDate('created_at',date('Y-m-d'))->select('musicid')->selectRaw('count(*) as total')->groupBy('musicid')->orderByDesc('total')->get();
        
        $totalAssessment = number_format(Assessment::count());

        // คำนวณหาหมวดหมู่คลื่นเสียงยอดนิยม
        $categories = FilterDetail::where('category_id',2)->withSum('music', 'play_count')->get();
        $grandTotalListens = $categories->sum('music_sum_play_count');
        
        $popularCategories = $categories->map(function ($category) use ($grandTotalListens) {
            $categoryListens = $category->music_sum_play_count ?? 0;
            $category->percentage = $grandTotalListens > 0 
                ? round(($categoryListens / $grandTotalListens) * 100) 
                : 0;
            return $category;
        })->sortByDesc('percentage')->values();


        // คำนวณหาค่าเฉลี่ยคนฟังคลื่นเสียงกี่นาทีต่อวัน
        $dailyUsers = History::whereNotNull('uid')
            ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT uid) as daily_count')
            ->groupBy('date')
            ->get()
            ->sum('daily_count');
        $durationdaily = History::whereNotNull('uid')->sum('play_duration');
        $AverageListen = $dailyUsers > 0 ? number_format(($durationdaily / 60)/ $dailyUsers,2) : 0;
        
        return view('admin.index', compact('popularCategories', 'totalListens', 'userNum','AverageListen','dailyListens','totalAssessment'));
    }
}
