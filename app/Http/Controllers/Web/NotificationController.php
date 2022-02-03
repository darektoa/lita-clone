<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{Notification, User};
use App\Notifications\PushNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Notification as Notif};
use RealRashid\SweetAlert\Facades\Alert;

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


    public function massive(Request $request) {
        try{
            $this->validate($request, [
                'title'     => 'required',
                'body'      => 'nullable',
                'recipient' => 'required|in:1,2,3',
            ]);

            $recipient  = (int) $request->recipient;
            $users      = new User();

            switch($recipient) {
                case 1:
                    $users      = $users->whereHas('player');
                    $recipient  = ['Player', 'Pro Player']; break;
                case 2:
                    $users = $users->whereRelation('player', 'is_pro_player', 0);
                    $recipient  = ['Player']; break;
                case 3:
                    $users = $users->whereRelation('player', 'is_pro_player', 1);
                    $recipient  = ['Pro Player']; break;
            }
            
            $users      = $users->orWhereHas('admin')->get();
            $payloads   = [
                'title'     => $request->title,
                'body'      => $request->body,
                'recipient' => $recipient,
                'admin_id'  => auth()->id(),
            ];

            Notif::send($users, new PushNotification($payloads));
            Alert::success('Success', 'Sent notifications successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
