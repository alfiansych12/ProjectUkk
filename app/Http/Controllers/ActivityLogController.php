<?php

namespace App\Http\Controllers;

use App\Models\AktivitasLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 5);
        $allowedPerPage = [5, 10, 15, 20];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        $logs = AktivitasLog::with('user')->latest()->paginate($perPage)->appends(['per_page' => $perPage]);
        return view('admin.log', compact('logs'));
    }
}
