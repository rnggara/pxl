<?php

namespace App\Http\Controllers;

use App\Models\File_Management;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DownloadController extends Controller
{
    function download($hash){
        $download = File_Management::where('hash_code', $hash)->first();
        return view('download.index', [
            'data' => $download,
            'jsondata' => json_encode($download->file_name)
        ]);
    }
}
