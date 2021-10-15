<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ProPlayerSkill;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProPlayerSkillController extends Controller
{
    public function index(Request $request) {
        $proPlayers = new ProPlayerSkill;
        $statusId   = $request->status;

        if($statusId >= 0 && $statusId <= 2)
            $proPlayers = $proPlayers->where('status', $statusId);
        
        $proPlayers = $proPlayers
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.pro-players.index', compact('proPlayers'));
    }


    public function approve(ProPlayerSkill $proPlayerSkill) {
        try{
            $proPlayerSkill->status = 2;
            $proPlayerSkill->player->is_pro_player = 1;
            $proPlayerSkill->player->update();
            $proPlayerSkill->update();
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

            Alert::success('Success', 'Successfully rejected to become a pro player');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
