<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('notifiable')->paginate();

        return view('pages.admin.notification.index', compact('notifications'));
    }
}
