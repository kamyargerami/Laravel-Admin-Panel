<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\DownloadRequest;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download(DownloadRequest $request)
    {
        $file_path = 'files/' . $request->product . '/' . $request->file;

        if (!Storage::exists($file_path)) {
            return response([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'file' => 'File not exist in this path.'
                ]
            ], 404);
        }

        return response()->download(storage_path('app/' . $file_path));
    }
}
