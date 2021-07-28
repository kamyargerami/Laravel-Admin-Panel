<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MobileVerifyController extends Controller
{
    public function form()
    {
        if (auth()->user()->mobile_verified_at)
            return redirect()->route('admin.index');

        // TODO send sms
        return view('pages.auth.mobile-verify');
    }

    public function verify(Request $request)
    {
        if (auth()->user()->mobile_verified_at)
            return redirect()->route('admin.index');

        $this->validate($request, [
            'code' => 'required|numeric'
        ]);

        // TODO check sms

        auth()->user()->update([
            'mobile_verified_at' => Carbon::now()->toDateTimeString()
        ]);

        return redirect()->route('admin.index');
    }
}
