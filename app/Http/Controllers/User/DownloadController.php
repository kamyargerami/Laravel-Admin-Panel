<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\DownloadRequest;
use App\Models\UsedLicence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download(DownloadRequest $request)
    {
        $used_licence = UsedLicence::with('license.product')->where(['username' => $request->username, 'password' => $request->password])->first();

        if (!$used_licence) {
            return response(['message' => 'Username or Password is incorrect'], 403);
        }

        $license = $used_licence->license;

        if (Carbon::parse($license->expires_at)->setTime(23, 59, 59)->isPast()) {
            return response(['message' => 'License expired'], 484);
        }

        $file_path = 'files/' . $license->product->name . '/' . $request->file;

        if (!Storage::exists($file_path)) {
            return response(['message' => 'File not exist in this path'], 404);
        }

        return response()->download(storage_path('app/' . $file_path));
    }
}
