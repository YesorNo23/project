<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    //
    public function def(){
        $logs = Log::all();
        return view('admin.log_def',compact('logs'));
    }
}
