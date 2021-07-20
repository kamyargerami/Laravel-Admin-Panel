<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')->orderByDesc('id')->paginate();
        return view('pages.admin.log.index', compact('logs'));
    }

    public function show($type, $id)
    {
        $logs = Log::with('user')->where(['loggable_type' => "App\Models\\$type", 'loggable_id' => $id])->orderByDesc('id')->get();

        return view('pages.admin.log.show', compact('type', 'id', 'logs'));
    }

    public function details(Log $log)
    {
        return response()->json($log);
    }
}
