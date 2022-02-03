<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index() {
        $userId         = auth()->id();
        $notifications  = Notification::whereHas('notifiable', function($query) use($userId) {
            $query->whereHas('admin')
                ->where('id', $userId);
        })->paginate(10);

        return view('pages.admin.notifications.index', compact('notifications'));
    }
}
