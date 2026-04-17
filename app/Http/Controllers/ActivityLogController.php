<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = AktivitasLog::with('user')->latest()->paginate(20);
        return view('admin.log', compact('logs'));
    }
}
