<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{Notification, User};
use App\Notifications\PushNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Notification as Notif};
use RealRashid\SweetAlert\Facades\Alert;

class NotificationController extends Controller
{
    public function index() {
        $userId         = auth()->id();
        $notifications  = Notification::whereHas('notifiable', function($query) use($userId) {
            $query->whereHas('admin')
                ->where('id', $userId);
        })
        ->latest()
        ->paginate(10);

        return view('pages.admin.notifications.index', compact('notifications'));
    }


    public function massive(Request $request) {
        try{
            $this->validate($request, [
                'title'     => 'required',
                'body'      => 'nullable',
                'recipient' => 'required|in:1,2,3',
                'history'   => 'required|in:0,1',
            ]);

            $history    = (bool) $request->history;
            $recipient  = (int) $request->recipient;
            $users      = User::with(['deviceIds']);

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
                'history'   => $history,
                'admin_id'  => auth()->id(),
            ];
            
            if($history)
                Notif::send($users, new PushNotification($payloads));
            else {
                $admins             = User::whereHas('admin')->get();
                $recipients         = Arr::flatten(Arr::pluck($users->toArray(), 'device_ids.*.device_id'));
                $payloads['body']   = strip_tags($request->body);
                
                fcm()->to($recipients)
                    ->timeToLive(86400) // 1 day
                    ->data($payloads)
                    ->notification($payloads)
                    ->send();
                
                Notif::send($admins, new PushNotification($payloads));
            }

            Alert::success('Success', 'Sent notifications successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
