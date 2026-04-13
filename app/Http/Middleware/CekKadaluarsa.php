<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FileEnkripsi;

class CekKadaluarsa
{
    public function handle(Request $request, Closure $next)
    {
        $fileId = $request->input('file_id');
        if (!$fileId) {
            return back()->with('error', 'ID dokumen tidak ditemukan.');
        }
        $file = FileEnkripsi::find($fileId);

        if (!$file) {
            return back()->with('error', 'Dokumen tidak ditemukan.');
        }
        if ($file->kadaluarsa_pada && $file->kadaluarsa_pada->isPast()) {
            return back()->with('error', 'File sudah kadaluarsa dan tidak dapat didekripsi.');
        }
        return $next($request);
    }
}