<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

abstract class Controller
{
    //
    public function saveLog($action = null, $module = null){
        Log::create([ 'uid'        => auth()->id() ?? null,
                      'action'     => $action,   
                      'module'     => $module,  
                      'endpoint'   => Request::fullUrl(), 
                      'ip_address' => Request::ip(),
                      'date'       => now()             ]);
    }
}
