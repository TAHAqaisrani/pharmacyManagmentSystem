<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EmailLogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            return view('admin.email-logs', ['emails' => []]);
        }
        
        $logContent = File::get($logPath);
        
        // Extract email entries from log
        $emails = [];
        $pattern = '/\[(\d{4}-\d{2}-\d{2}[^\]]+)\].*?To: ([^\n]+).*?Subject: ([^\n]+)/s';
        
        preg_match_all($pattern, $logContent, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $emails[] = [
                'timestamp' => $match[1],
                'to' => trim($match[2]),
                'subject' => trim($match[3]),
            ];
        }
        
        // Reverse to show newest first
        $emails = array_reverse($emails);
        
        return view('admin.email-logs', compact('emails'));
    }
    
    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }
        
        return redirect()->route('admin.email-logs.index')->with('success', 'Email logs cleared successfully');
    }
}
