<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\{ProPlayerSkill, Tier};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use RealRashid\SweetAlert\Facades\Alert;

class ProPlayerSkillController extends Controller
{
    public function index(Request $request) {
        $search     = $request->search;
        $proPlayers = new ProPlayerSkill;
        $statusId   = $request->status;
        $total      = [
            'all'       => $proPlayers->count(),
            'pending'   => $proPlayers->where('status', 0)->count(),
            'rejected'  => $proPlayers->where('status', 1)->count(),  
            'approved'  => $proPlayers->where('status', 2)->count(),
        ];

        if($statusId !== null & $statusId >= 0 && $statusId <= 2)
            $proPlayers = $proPlayers->where('status', $statusId);
        if($search)
            $proPlayers  = $proPlayers
            ->whereHas('player', function($query) use($search) {
                $query->whereRelation('user', 'username', 'LIKE', "%$search%")
                    ->orWhereRelation('user', 'email', 'LIKE', "%$search%")
                    ->orWhereRelation('user', 'name', 'LIKE', "%$search%");
            });
        
        $proPlayers = $proPlayers
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.pro-players.index', compact('proPlayers', 'total', 'search'));
    }


    public function approve(ProPlayerSkill $proPlayerSkill) {
        try{
            $tier = Tier::orderBy('min_order', 'desc')
                ->where('min_order', '<=', 0)
                ->first();

            $proPlayerSkill->tier_id = $tier->id;
            $proPlayerSkill->status  = 2;
            $proPlayerSkill->player->is_pro_player = 1;
            $proPlayerSkill->player->update();
            $proPlayerSkill->update();

            // SEND PUSH NOTIFICATION
            $recipients = Arr::flatten([
                $proPlayerSkill
                ->player
                ->user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            ]);

            $payloads = [
                'title' => 'Pengajuan Pro Player Disetujui !',
                'body'  => "Pengajuan menjadi pro player game [{$proPlayerSkill->game->name}] disetujui"
            ];

            fcm()->to($recipients) // Must an array
            ->timeToLive(2419200) // 28 days
            ->data($payloads)
            ->notification($payloads)
            ->send();

            Alert::success('Success', 'Successfully made a pro player');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }


    public function reject(ProPlayerSkill $proPlayerSkill) {
        try{
            $player = $proPlayerSkill->player;
            $proPlayerSkill->status = 1;
            $proPlayerSkill->update();
            $player->update();
            
            if($player->proPlayerSkills->where('status', '!=', 0)->count() === 0)
                $player->is_pro_player = 0;

            // SEND PUSH NOTIFICATION
            $recipients = Arr::flatten([
                $proPlayerSkill
                ->player
                ->user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            ]);

            $payloads = [
                'title' => 'Pengajuan Pro Player Ditolak',
                'body'  => "Mohon maaf, kamu bisa ajukan kembali menjadi pro player di game [{$proPlayerSkill->game->name}]"
            ];

            fcm()->to($recipients) // Must an array
            ->timeToLive(2419200) // 28 days
            ->data($payloads)
            ->notification($payloads)
            ->send();

            Alert::success('Success', 'Successfully rejected to become a pro player');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
