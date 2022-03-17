<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CollectionHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProPlayerServiceResource;
use App\Models\ProPlayerService;
use Illuminate\Http\Request;

class ProPlayerServiceController extends Controller
{
    public function index() {
        $user       = auth()->user() ?? null;
        $userGender = $user->gender->id ?? null;
        $services   = ProPlayerService::with(['player.user.gender', 'service'])
            ->where('status', 2);

        if($userGender === 1){
            $services = $services->get()->sortByDesc(fn($query) => (
                $query->player->user->gender_id === 2 // Female
            ));
            $services = CollectionHelper::paginate($services, 10);
        } else {
            $services = $services->get()->shuffle();
        }

        return ResponseHelper::make(
            ProPlayerServiceResource::collection($services)
        );
    }
}
